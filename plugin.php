<?php defined('BASEPATH') or exit('No direct script access allowed');

class Plugin_Galleries extends Plugin
{
	public function __construct()
	{
		$this->load->model(array('galleries/galleries_m', 'galleries/images_m'));
	}

	public function get()
	{
		$slug = $this->attribute('slug', false);
		$id   = $this->attribute('id', false) or $this->galleries_m->id_from_slug($slug);

		if ( ! $id) return false;

		return $this->galleries_m->get($id);
	}

	public function all()
	{
		$with_images = $this->attribute('with_images') == 'yes';

		return $this->galleries_m->get_all($with_images);
	}

	public function find()
	{
		$with_images = $this->attribute('with_images') == 'yes';
		$where       = $this->build_where($this->attributes());

		return $this->galleries_m->get_all($with_images, $where);
	}

	protected function build_where($conditions)
	{
		foreach ($conditions as $key => $value) {

			if ($key == 'where') {
				$where .= trim($value).' and ';
			} elseif ( ! in_array($key, array('parse_params', 'with_images'))) {
				$where .= "{$key} = '{$value}' and ";
			}

		}

		return trim($where, 'and ');
	}
}