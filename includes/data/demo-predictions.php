<?php
namespace ADPM\iTipsterPro\Data;

/**
 * Demo predictions data
 * 
 * @package ADPM\iTipsterPro
 */
class DemoPredictions {
    
    /**
     * Get demo predictions data
     */
    public static function get_predictions() {
        return array(
            array(
                'fixture_id' => 101,
                'prediction_type' => 'Match Winner',
                'prediction_value' => 'Home Win',
                'confidence_score' => 85.5,
                'odds' => 1.85,
                'value_rating' => 8.5,
                'analysis' => 'Manchester City has been dominant at home this season with 8 wins from 9 games. Their attacking trio of Haaland, De Bruyne, and Foden is in excellent form.',
                'trends' => array(
                    'home_form' => 'WWWWW',
                    'away_form' => 'WDLWW',
                    'h2h' => 'City won 3 of last 5 meetings',
                    'goals_per_game' => 2.8
                )
            ),
            array(
                'fixture_id' => 102,
                'prediction_type' => 'Total Goals',
                'prediction_value' => 'Over 2.5',
                'confidence_score' => 78.2,
                'odds' => 1.95,
                'value_rating' => 7.8,
                'analysis' => 'Both Arsenal and Chelsea have scored in their last 5 meetings. Arsenal has averaged 2.1 goals per game at home this season.',
                'trends' => array(
                    'home_form' => 'WWLWW',
                    'away_form' => 'DLWLD',
                    'h2h' => 'Both teams scored in 4 of last 5',
                    'goals_per_game' => 3.2
                )
            ),
            array(
                'fixture_id' => 103,
                'prediction_type' => 'Both Teams to Score',
                'prediction_value' => 'Yes',
                'confidence_score' => 82.1,
                'odds' => 1.75,
                'value_rating' => 8.2,
                'analysis' => 'Manchester United and Tottenham both have strong attacking records. United has scored in 8 of their last 10 home games.',
                'trends' => array(
                    'home_form' => 'WLWWL',
                    'away_form' => 'WWDLW',
                    'h2h' => 'Both teams scored in 3 of last 4',
                    'goals_per_game' => 2.9
                )
            ),
            array(
                'fixture_id' => 201,
                'prediction_type' => 'Match Winner',
                'prediction_value' => 'Home Win',
                'confidence_score' => 72.8,
                'odds' => 2.10,
                'value_rating' => 7.3,
                'analysis' => 'Real Madrid has won 6 of their last 7 home games. Their midfield control and attacking options give them the edge in El Clasico.',
                'trends' => array(
                    'home_form' => 'WWWWL',
                    'away_form' => 'WWLWW',
                    'h2h' => 'Madrid won 2 of last 3 at home',
                    'goals_per_game' => 2.6
                )
            ),
            array(
                'fixture_id' => 202,
                'prediction_type' => 'Under 2.5 Goals',
                'prediction_value' => 'Yes',
                'confidence_score' => 68.5,
                'odds' => 2.25,
                'value_rating' => 6.9,
                'analysis' => 'Atletico Madrid\'s defensive solidity combined with Sevilla\'s cautious approach should result in a low-scoring affair.',
                'trends' => array(
                    'home_form' => 'WDLWW',
                    'away_form' => 'LDLWW',
                    'h2h' => 'Under 2.5 in 3 of last 4',
                    'goals_per_game' => 1.8
                )
            )
        );
    }
    
    /**
     * Get predictions by fixture ID
     */
    public static function get_predictions_by_fixture($fixture_id) {
        $predictions = self::get_predictions();
        
        foreach ($predictions as $prediction) {
            if ($prediction['fixture_id'] == $fixture_id) {
                return array($prediction);
            }
        }
        
        return array();
    }
    
    /**
     * Get high confidence predictions
     */
    public static function get_high_confidence_predictions($min_confidence = 80) {
        $predictions = self::get_predictions();
        $high_confidence = array();
        
        foreach ($predictions as $prediction) {
            if ($prediction['confidence_score'] >= $min_confidence) {
                $high_confidence[] = $prediction;
            }
        }
        
        return $high_confidence;
    }
    
    /**
     * Get value bets
     */
    public static function get_value_bets($min_value = 7.0) {
        $predictions = self::get_predictions();
        $value_bets = array();
        
        foreach ($predictions as $prediction) {
            if ($prediction['value_rating'] >= $min_value) {
                $value_bets[] = $prediction;
            }
        }
        
        return $value_bets;
    }
} 