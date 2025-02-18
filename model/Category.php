<?php
namespace com\cminds\videolesson\model;

class Category extends TaxonomyTerm {

	const TAXONOMY = 'cmvl_category';
	
	static function init() {
		parent::init();
		
		// Register taxonomy
		$args = array(
            'hierarchical' => TRUE,
            'labels' => self::getTaxonomyLabels(),
            'show_ui' => FALSE, // to override in pro
            'query_var' => TRUE,
			'show_admin_column' => true,
			//'post_types' => array(Channel::POST_TYPE),
			'post_types' => array(),
			'object_type' => Channel::POST_TYPE,
			'public' => false,
			'with_front' => false,
			'show_in_rest' => true, // for Guttenberg compatibility
			//'rewrite' => array('slug' => Settings::getOption(Settings::OPTION_PERMALINK_PREFIX) .'/category'),
        );
		register_taxonomy(self::TAXONOMY, $args['post_types'], apply_filters('cmvl_category_term_args', $args));
		
		// Create General category if no categories exists
		global $wpdb;
		$count = intval($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->term_taxonomy WHERE taxonomy = %s", self::TAXONOMY)));
		if ($count == 0) \wp_insert_term('All Videos', self::TAXONOMY);
		
	}
	
	static function getTaxonomyLabels() {
		$plural = ucfirst(Labels::getLocalized('video_categories'));
		$singular = ucfirst(Labels::getLocalized('video_category'));
        return array(
            'name' => $plural,
            'singular_name' => $singular,
            'search_items' => 'Search ' . $plural,
            'popular_items' => 'Popular ' . $plural,
            'all_items' => 'All ' . $plural,
            'parent_item' => 'Parent ' . $singular,
            'parent_item_colon' => 'Parent ' . $singular . ':',
            'edit_item' => 'Edit ' . $singular,
            'update_item' => 'Update ' . $singular,
            'add_new_item' => 'Add New ' . $singular,
            'new_item_name' => 'New ' . $singular . ' Name',
            'menu_name' => $plural,
        );
    }
    
    /**
	 * Get instance
	 * 
	 * @param object|int $term Term object or ID
	 * @return com\cminds\videolesson\model\Category
	 */
	static function getInstance($term) {
		return parent::getInstance($term);
	}
	
	/**
	 * 
	 * @return array<Channel>
	 */
	function getChannels($queryArgs = array()) {
		$queryArgs = array_merge(array(
			'post_type' => Channel::POST_TYPE,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'post_title',
			'order' => 'asc',
			'tax_query' => array(
				array(
	        		'taxonomy' => static::TAXONOMY,
	        		'field' => 'term_id',
	        		'terms' => array($this->getId()),
	        	)
			),
		), $queryArgs);
		$query = new \WP_Query($queryArgs);
		$channels = $query->get_posts();
		foreach ($channels as &$channel) {
			$channel = Channel::getInstance($channel);
		}
		return $channels;
	}
	
	function getFirstChannelPermalink() {
		if ($channel = $this->getFirstChannel()) {
			return $channel->getPermalinkForCategory($this);
		}
	}
	
	/**
	 * 
	 * @return com\cminds\videolesson\model\Channel
	 */
	function getFirstChannel() {
		$channel = $this->getChannels(array('posts_per_page' => 1));
		return reset($channel);
	}
	
	function getEditUrl() {
		return admin_url(sprintf('edit-tags.php?action=edit&taxonomy=%s&tag_ID=%d&post_type=%s',
			Category::TAXONOMY,
			$this->getId(),
			Channel::POST_TYPE
		));
	}
	
}