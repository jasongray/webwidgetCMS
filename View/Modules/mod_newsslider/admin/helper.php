<?php 

/**
 *	mod_newsflash class
 *
 *
 */

class mod_newsslider  {

	public function getCategories() {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$menu = $this->Category->generateTreeList(array('Category.published' => 1, 'Category.type' => 2), null, null, ' - ');
		if ($menu) {
			return $menu;
		}
		return array();
	}

	public function getPosts($m) {
		$conditions = array(); $limit = array();
		if (isset($m['category_id']) && !empty($m['category_id'])) {
			$conditions = array_merge(array('News.category_id' => $m['category_id']), $conditions);
		}
		if (isset($m['featured']) && !empty($m['featured'])) {
			$conditions = array_merge(array('News.featured' => $m['featured']), $conditions);
		}
		if (isset($m['bloglimit'])) {
			$limit = array('limit' => $m['bloglimit']);
		}
		App::uses('News', 'Model');
		$this->News = new News;
		$this->News->recursive = 0;
		return $this->News->find('all', array_merge(array(
			'fields' => array(
				'News.id',
				'News.title',
				'News.subheading',
				'News.intro_text',
				'News.image',
				'News.start_publish',
				'News.author',
				'Category.id',
				'Category.title',
			),
			'conditions' => array_merge(array('News.published' => 1, 'News.image IS NOT NULL', 'IF(News.end_publish IS NOT NULL, NOW() BETWEEN News.start_publish AND News.end_publish, 1)'), $conditions),
			'order' => 'News.start_publish DESC, News.created DESC',
			), 
			$limit)
		);
	}

}
?>