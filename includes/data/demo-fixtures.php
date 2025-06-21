<?php
namespace ADPM\iTipsterPro\Data;

/**
 * Demo fixtures data
 * 
 * @package ADPM\iTipsterPro
 */
class DemoFixtures {
    
    /**
     * Get demo fixtures data
     */
    public static function get_fixtures() {
        return array(
            'Premier League' => array(
                array(
                    'id' => 101,
                    'home_team' => 'Manchester City',
                    'away_team' => 'Liverpool',
                    'date' => '2024-01-15 20:00:00',
                    'venue' => 'Etihad Stadium',
                    'league' => 'Premier League',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'Top of the table clash between two title contenders. City has been dominant at home this season with 8 wins from 9 games.',
                    'competition' => 'Premier League'
                ),
                array(
                    'id' => 102,
                    'home_team' => 'Arsenal',
                    'away_team' => 'Chelsea',
                    'date' => '2024-01-16 19:45:00',
                    'venue' => 'Emirates Stadium',
                    'league' => 'Premier League',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'London derby with both teams looking to climb the table. Arsenal has won their last 3 home games.',
                    'competition' => 'Premier League'
                ),
                array(
                    'id' => 103,
                    'home_team' => 'Manchester United',
                    'away_team' => 'Tottenham',
                    'date' => '2024-01-17 20:00:00',
                    'venue' => 'Old Trafford',
                    'league' => 'Premier League',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'Historic rivalry between two of England\'s biggest clubs. United has a strong home record this season.',
                    'competition' => 'Premier League'
                ),
                array(
                    'id' => 104,
                    'home_team' => 'Newcastle',
                    'away_team' => 'Aston Villa',
                    'date' => '2024-01-18 19:45:00',
                    'venue' => 'St James\' Park',
                    'league' => 'Premier League',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'Both teams are in good form and looking to secure European qualification spots.',
                    'competition' => 'Premier League'
                ),
                array(
                    'id' => 105,
                    'home_team' => 'Brighton',
                    'away_team' => 'West Ham',
                    'date' => '2024-01-19 15:00:00',
                    'venue' => 'Amex Stadium',
                    'league' => 'Premier League',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'Brighton\'s attacking style vs West Ham\'s defensive solidity. Goals expected.',
                    'competition' => 'Premier League'
                )
            ),
            'La Liga' => array(
                array(
                    'id' => 201,
                    'home_team' => 'Real Madrid',
                    'away_team' => 'Barcelona',
                    'date' => '2024-01-17 21:00:00',
                    'venue' => 'Santiago Bernabéu',
                    'league' => 'La Liga',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'El Clasico - The biggest rivalry in world football. Both teams are in excellent form.',
                    'competition' => 'La Liga'
                ),
                array(
                    'id' => 202,
                    'home_team' => 'Atletico Madrid',
                    'away_team' => 'Sevilla',
                    'date' => '2024-01-18 20:00:00',
                    'venue' => 'Metropolitano',
                    'league' => 'La Liga',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'Atletico\'s defensive strength vs Sevilla\'s attacking flair. A tactical battle expected.',
                    'competition' => 'La Liga'
                ),
                array(
                    'id' => 203,
                    'home_team' => 'Valencia',
                    'away_team' => 'Villarreal',
                    'date' => '2024-01-19 18:30:00',
                    'venue' => 'Mestalla',
                    'league' => 'La Liga',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'Valencian derby with both teams fighting for mid-table positions.',
                    'competition' => 'La Liga'
                ),
                array(
                    'id' => 204,
                    'home_team' => 'Athletic Bilbao',
                    'away_team' => 'Real Sociedad',
                    'date' => '2024-01-20 16:00:00',
                    'venue' => 'San Mamés',
                    'league' => 'La Liga',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'Basque derby with intense local rivalry and passionate atmosphere.',
                    'competition' => 'La Liga'
                ),
                array(
                    'id' => 205,
                    'home_team' => 'Girona',
                    'away_team' => 'Real Betis',
                    'date' => '2024-01-21 12:00:00',
                    'venue' => 'Montilivi',
                    'league' => 'La Liga',
                    'sport' => 'Football',
                    'status' => 'scheduled',
                    'description' => 'Girona\'s surprise title challenge continues against Betis\' European ambitions.',
                    'competition' => 'La Liga'
                )
            )
        );
    }
} 