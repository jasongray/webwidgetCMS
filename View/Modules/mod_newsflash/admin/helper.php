<?php 

/**
 *	mod_newsflash class
 *
 *
 */

class mod_newsflash  {

	public function getCategories() {
		App::uses('Category', 'Model');
		$this->Category = new Category;
		$menu = $this->Category->generateTreeList(array('Category.published' => 1, 'Category.type' => 2), null, null, ' - ');
		if ($menu) {
			return $menu;
		}
		return array();
	}

	public function getTmpl() {
		$out = array();
		App::uses('Folder', 'Utility');
		$_folder = (Configure::read('MySite.theme'))? 'Themed' . DS . Configure::read('MySite.theme') . DS . 'Modules': 'Modules';
		$dir = new Folder(APP . 'View' . DS . $_folder . DS . 'mod_newsflash' . DS . 'tmpl');
		$files = $dir->read();
		if (!empty($files[1])) {
			foreach ($files[1] as $f) { 
				$out[$f] = str_replace('.ctp', '', $f);
			}
		}
		return $out;
	}

	public function getPosts($m) {
		$conditions = array(); $limit = array();
		if (isset($m['category_id'])) {
			$conditions = array('News.category_id' => $m['category_id']);
		}
		if (isset($m['bloglimit'])) {
			$limit = array('limit' => $m['bloglimit']);
		}
		App::uses('News', 'Model');
		$this->News = new News;
		$this->News->recursive = 0;
		$this->News->virtualFields = array('comments' => 'SELECT COUNT(Comment.id) FROM comments AS Comment WHERE Comment.news_id = News.id AND Comment.status = 1');
		return $this->News->find('all', array_merge(array(
			'fields' => array(
				'News.id',
				'News.title',
				'News.subheading',
				'News.intro_text',
				'News.image',
				'News.start_publish',
				'Category.id',
				'Category.title',
				'News.comments',
			),
			'conditions' => array_merge(array('News.published' => 1, 'IF(News.end_publish IS NOT NULL, NOW() BETWEEN News.start_publish AND News.end_publish, 1)'), $conditions),
			'order' => 'News.start_publish DESC, News.created DESC',
			), 
			$limit)
		);
	}

}
?>