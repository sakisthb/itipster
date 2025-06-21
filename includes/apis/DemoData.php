<?php
namespace ADPM\iTipsterPro\APIs;

require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/data/demo-fixtures.php';
require_once ITIPSTER_PRO_PLUGIN_PATH . 'includes/data/demo-predictions.php';

use ADPM\iTipsterPro\Data\DemoFixtures;
use ADPM\iTipsterPro\Data\DemoPredictions;

/**
 * Demo Data class for providing sample data
 * 
 * @package ADPM\iTipsterPro
 */
class DemoData {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize demo data
    }
    
    /**
     * Insert demo data into database
     */
    public function insert_demo_data() {
        $this->insert_demo_fixtures();
        $this->insert_demo_predictions();
        $this->insert_demo_taxonomies();
    }
    
    /**
     * Insert demo fixtures
     */
    private function insert_demo_fixtures() {
        $fixtures = DemoFixtures::get_fixtures();
        
        foreach ($fixtures as $league_key => $league_fixtures) {
            foreach ($league_fixtures as $fixture) {
                $post_data = array(
                    'post_title' => $fixture['home_team'] . ' vs ' . $fixture['away_team'],
                    'post_content' => $this->generate_fixture_content($fixture),
                    'post_status' => 'publish',
                    'post_type' => 'itipster_fixture',
                    'post_date' => $fixture['date']
                );
                
                $post_id = wp_insert_post($post_data);
                
                if ($post_id) {
                    // Add league taxonomy
                    wp_set_object_terms($post_id, $fixture['league'], 'itipster_league');
                    
                    // Add sport taxonomy
                    wp_set_object_terms($post_id, $fixture['sport'], 'itipster_sport');
                    
                    // Add custom fields
                    update_post_meta($post_id, '_fixture_id', $fixture['id']);
                    update_post_meta($post_id, '_home_team', $fixture['home_team']);
                    update_post_meta($post_id, '_away_team', $fixture['away_team']);
                    update_post_meta($post_id, '_venue', $fixture['venue']);
                    update_post_meta($post_id, '_status', $fixture['status']);
                }
            }
        }
    }
    
    /**
     * Insert demo predictions
     */
    private function insert_demo_predictions() {
        global $wpdb;
        
        $predictions = DemoPredictions::get_predictions();
        
        foreach ($predictions as $prediction) {
            $wpdb->insert(
                $wpdb->prefix . 'itipster_predictions',
                array(
                    'fixture_id' => $prediction['fixture_id'],
                    'prediction_type' => $prediction['prediction_type'],
                    'prediction_value' => $prediction['prediction_value'],
                    'confidence_score' => $prediction['confidence_score'],
                    'odds' => $prediction['odds'],
                    'value_rating' => $prediction['value_rating'],
                    'analysis' => $prediction['analysis'],
                    'trends' => json_encode($prediction['trends'])
                ),
                array('%d', '%s', '%s', '%f', '%f', '%f', '%s', '%s')
            );
        }
    }
    
    /**
     * Insert demo taxonomies
     */
    private function insert_demo_taxonomies() {
        // Insert leagues
        $leagues = array(
            'Premier League' => 'premier-league',
            'La Liga' => 'la-liga',
            'Bundesliga' => 'bundesliga',
            'Serie A' => 'serie-a',
            'Ligue 1' => 'ligue-1'
        );
        
        foreach ($leagues as $name => $slug) {
            if (!term_exists($name, 'itipster_league')) {
                wp_insert_term($name, 'itipster_league', array('slug' => $slug));
            }
        }
        
        // Insert sports
        $sports = array(
            'Football' => 'football',
            'Basketball' => 'basketball',
            'Tennis' => 'tennis'
        );
        
        foreach ($sports as $name => $slug) {
            if (!term_exists($name, 'itipster_sport')) {
                wp_insert_term($name, 'itipster_sport', array('slug' => $slug));
            }
        }
    }
    
    /**
     * Get upcoming fixtures
     */
    public function get_upcoming_fixtures($league = '', $limit = 20) {
        $fixtures = DemoFixtures::get_fixtures();
        $upcoming = array();
        
        foreach ($fixtures as $league_key => $league_fixtures) {
            if (empty($league) || strtolower($league_key) === strtolower($league)) {
                $upcoming = array_merge($upcoming, array_slice($league_fixtures, 0, $limit));
            }
        }
        
        return array_slice($upcoming, 0, $limit);
    }
    
    /**
     * Get live odds
     */
    public function get_live_odds($fixture_id) {
        // Generate realistic live odds
        $base_odds = array(
            'home' => rand(150, 350) / 100,
            'draw' => rand(300, 450) / 100,
            'away' => rand(200, 400) / 100
        );
        
        // Add some variation
        $variation = rand(-20, 20) / 100;
        
        return array(
            'home' => round($base_odds['home'] + $variation, 2),
            'draw' => round($base_odds['draw'] + $variation, 2),
            'away' => round($base_odds['away'] + $variation, 2),
            'last_updated' => current_time('mysql'),
            'bookmaker' => $this->get_random_bookmaker()
        );
    }
    
    /**
     * Get AI predictions
     */
    public function get_ai_predictions($fixture_id) {
        $predictions = DemoPredictions::get_predictions_by_fixture($fixture_id);
        
        if (empty($predictions)) {
            // Generate a default prediction
            return array(
                array(
                    'fixture_id' => $fixture_id,
                    'prediction_type' => '1X2',
                    'prediction_value' => '1',
                    'confidence_score' => rand(65, 90),
                    'odds' => rand(150, 300) / 100,
                    'value_rating' => rand(60, 95) / 10,
                    'analysis' => 'AI analysis based on team form and statistics.',
                    'market' => 'Match Result'
                )
            );
        }
        
        return $predictions;
    }
    
    /**
     * Get value bets
     */
    public function get_value_bets($league = '', $limit = 10) {
        $predictions = DemoPredictions::get_predictions();
        $value_bets = array();
        
        foreach ($predictions as $prediction) {
            if ($prediction['value_rating'] >= 7.0) {
                if (empty($league) || $this->get_fixture_league($prediction['fixture_id']) === $league) {
                    $value_bets[] = $prediction;
                }
            }
        }
        
        // Sort by value rating
        usort($value_bets, function($a, $b) {
            return $b['value_rating'] <=> $a['value_rating'];
        });
        
        return array_slice($value_bets, 0, $limit);
    }
    
    /**
     * Get team trends
     */
    public function get_team_trends($team_id) {
        return array(
            'form' => $this->generate_form_string(),
            'last_matches' => $this->generate_last_matches(),
            'goals_scored' => rand(15, 45),
            'goals_conceded' => rand(10, 35),
            'clean_sheets' => rand(3, 12),
            'failed_to_score' => rand(2, 8)
        );
    }
    
    /**
     * Get probability
     */
    public function get_probability($fixture_id) {
        return array(
            'home_win' => rand(35, 65),
            'draw' => rand(20, 35),
            'away_win' => rand(15, 45),
            'over_2_5' => rand(45, 75),
            'both_teams_score' => rand(55, 85)
        );
    }
    
    /**
     * Get betslips
     */
    public function get_betslips($type = 'trending', $limit = 5) {
        $betslips = array();
        
        for ($i = 1; $i <= $limit; $i++) {
            $betslips[] = array(
                'id' => $i,
                'name' => $this->generate_betslip_name(),
                'type' => $type,
                'selections' => rand(3, 8),
                'total_odds' => rand(200, 1500) / 100,
                'stake' => rand(5, 50),
                'potential_win' => rand(10, 200)
            );
        }
        
        return $betslips;
    }
    
    /**
     * Generate fixture content
     */
    private function generate_fixture_content($fixture) {
        return sprintf(
            'Match between %s and %s at %s. %s',
            $fixture['home_team'],
            $fixture['away_team'],
            $fixture['venue'],
            $fixture['description']
        );
    }
    
    /**
     * Get random bookmaker
     */
    private function get_random_bookmaker() {
        $bookmakers = array('Bet365', 'William Hill', 'Ladbrokes', 'Paddy Power', 'Betfair');
        return $bookmakers[array_rand($bookmakers)];
    }
    
    /**
     * Generate form string
     */
    private function generate_form_string() {
        $form_chars = array('W', 'D', 'L');
        $form = '';
        
        for ($i = 0; $i < 5; $i++) {
            $form .= $form_chars[array_rand($form_chars)];
        }
        
        return $form;
    }
    
    /**
     * Generate last matches
     */
    private function generate_last_matches() {
        $matches = array();
        
        for ($i = 0; $i < 5; $i++) {
            $matches[] = array(
                'opponent' => 'Team ' . rand(1, 20),
                'result' => rand(0, 2), // 0 = loss, 1 = draw, 2 = win
                'score' => rand(0, 3) . '-' . rand(0, 3)
            );
        }
        
        return $matches;
    }
    
    /**
     * Generate betslip name
     */
    private function generate_betslip_name() {
        $names = array(
            'Weekend Winners',
            'Midweek Magic',
            'European Elite',
            'Premier Picks',
            'La Liga Legends',
            'Bundesliga Best',
            'Serie A Stars',
            'Ligue 1 Leaders'
        );
        
        return $names[array_rand($names)];
    }
    
    /**
     * Get fixture league
     */
    private function get_fixture_league($fixture_id) {
        // This would normally query the database
        // For demo purposes, return a random league
        $leagues = array('Premier League', 'La Liga', 'Bundesliga', 'Serie A', 'Ligue 1');
        return $leagues[array_rand($leagues)];
    }
} 