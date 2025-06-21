<?php
namespace ADPM\iTipsterPro;

/**
 * Predictions class for managing prediction data
 * 
 * @package ADPM\iTipsterPro
 */
class Predictions {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_ajax_itipster_create_prediction', array($this, 'ajax_create_prediction'));
        add_action('wp_ajax_itipster_update_prediction', array($this, 'ajax_update_prediction'));
        add_action('wp_ajax_itipster_delete_prediction', array($this, 'ajax_delete_prediction'));
        add_action('wp_ajax_itipster_get_prediction', array($this, 'ajax_get_prediction'));
    }
    
    /**
     * Get predictions
     */
    public function get_predictions($league = '', $sport = '', $limit = 10) {
        global $wpdb;
        
        $where_conditions = array();
        $where_values = array();
        
        if (!empty($league)) {
            $where_conditions[] = 'league = %s';
            $where_values[] = $league;
        }
        
        if (!empty($sport)) {
            $where_conditions[] = 'sport = %s';
            $where_values[] = $sport;
        }
        
        $where_clause = '';
        if (!empty($where_conditions)) {
            $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
        }
        
        $query = "SELECT * FROM {$wpdb->prefix}itipster_predictions {$where_clause} ORDER BY created_at DESC LIMIT %d";
        $where_values[] = $limit;
        
        if (!empty($where_values)) {
            $predictions = $wpdb->get_results($wpdb->prepare($query, $where_values));
        } else {
            $predictions = $wpdb->get_results($wpdb->prepare($query, $limit));
        }
        
        return $predictions;
    }
    
    /**
     * Get prediction by ID
     */
    public function get_prediction($id) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare("
            SELECT * FROM {$wpdb->prefix}itipster_predictions 
            WHERE id = %d
        ", $id));
    }
    
    /**
     * Create prediction
     */
    public function create_prediction($data) {
        global $wpdb;
        
        $defaults = array(
            'fixture_id' => 0,
            'prediction_type' => '',
            'prediction_value' => '',
            'confidence_score' => 0,
            'odds' => 0,
            'value_rating' => 0,
            'created_at' => current_time('mysql')
        );
        
        $data = wp_parse_args($data, $defaults);
        
        $result = $wpdb->insert(
            $wpdb->prefix . 'itipster_predictions',
            array(
                'fixture_id' => $data['fixture_id'],
                'prediction_type' => $data['prediction_type'],
                'prediction_value' => $data['prediction_value'],
                'confidence_score' => $data['confidence_score'],
                'odds' => $data['odds'],
                'value_rating' => $data['value_rating'],
                'created_at' => $data['created_at']
            ),
            array('%d', '%s', '%s', '%f', '%f', '%f', '%s')
        );
        
        if ($result === false) {
            return false;
        }
        
        return $wpdb->insert_id;
    }
    
    /**
     * Update prediction
     */
    public function update_prediction($id, $data) {
        global $wpdb;
        
        $result = $wpdb->update(
            $wpdb->prefix . 'itipster_predictions',
            $data,
            array('id' => $id),
            array('%s', '%s', '%f', '%f', '%f'),
            array('%d')
        );
        
        return $result !== false;
    }
    
    /**
     * Delete prediction
     */
    public function delete_prediction($id) {
        global $wpdb;
        
        return $wpdb->delete(
            $wpdb->prefix . 'itipster_predictions',
            array('id' => $id),
            array('%d')
        );
    }
    
    /**
     * AJAX create prediction
     */
    public function ajax_create_prediction() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $data = array(
            'fixture_id' => intval($_POST['fixture_id']),
            'prediction_type' => sanitize_text_field($_POST['prediction_type']),
            'prediction_value' => sanitize_text_field($_POST['prediction_value']),
            'confidence_score' => floatval($_POST['confidence_score']),
            'odds' => floatval($_POST['odds']),
            'value_rating' => floatval($_POST['value_rating'])
        );
        
        $prediction_id = $this->create_prediction($data);
        
        if ($prediction_id) {
            wp_send_json_success(array('id' => $prediction_id));
        } else {
            wp_send_json_error('Failed to create prediction');
        }
    }
    
    /**
     * AJAX update prediction
     */
    public function ajax_update_prediction() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $id = intval($_POST['id']);
        $data = array(
            'prediction_type' => sanitize_text_field($_POST['prediction_type']),
            'prediction_value' => sanitize_text_field($_POST['prediction_value']),
            'confidence_score' => floatval($_POST['confidence_score']),
            'odds' => floatval($_POST['odds']),
            'value_rating' => floatval($_POST['value_rating'])
        );
        
        $result = $this->update_prediction($id, $data);
        
        if ($result) {
            wp_send_json_success('Prediction updated successfully');
        } else {
            wp_send_json_error('Failed to update prediction');
        }
    }
    
    /**
     * AJAX delete prediction
     */
    public function ajax_delete_prediction() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $id = intval($_POST['id']);
        $result = $this->delete_prediction($id);
        
        if ($result) {
            wp_send_json_success('Prediction deleted successfully');
        } else {
            wp_send_json_error('Failed to delete prediction');
        }
    }
    
    /**
     * AJAX get prediction
     */
    public function ajax_get_prediction() {
        check_ajax_referer('itipster_nonce', 'nonce');
        
        $id = intval($_POST['id']);
        $prediction = $this->get_prediction($id);
        
        if ($prediction) {
            wp_send_json_success($prediction);
        } else {
            wp_send_json_error('Prediction not found');
        }
    }
    
    /**
     * Get prediction statistics
     */
    public function get_statistics() {
        global $wpdb;
        
        $stats = array();
        
        // Total predictions
        $stats['total'] = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}itipster_predictions");
        
        // Average confidence
        $stats['avg_confidence'] = $wpdb->get_var("SELECT AVG(confidence_score) FROM {$wpdb->prefix}itipster_predictions");
        
        // Average value rating
        $stats['avg_value_rating'] = $wpdb->get_var("SELECT AVG(value_rating) FROM {$wpdb->prefix}itipster_predictions");
        
        // Predictions by type
        $stats['by_type'] = $wpdb->get_results("
            SELECT prediction_type, COUNT(*) as count 
            FROM {$wpdb->prefix}itipster_predictions 
            GROUP BY prediction_type
        ");
        
        return $stats;
    }
} 