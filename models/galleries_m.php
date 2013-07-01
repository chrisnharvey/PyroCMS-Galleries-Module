<?php defined('BASEPATH') or exit('No direct script access');

class Galleries_m extends MY_Model
{
	public function get($id, $images = true)
	{
		$data = $this->streams->entries->get_entry($id, 'galleries', 'galleries');

		if ($images) $data->images = $this->get_images($data->id);

		return $data;
	}

	public function get_all($images = false, $where = null)
	{
		$entries = $this->streams->entries->get_entries(array(
			'stream'    => 'galleries',
			'namespace' => 'galleries',
			'order_by'  => 'ordering_count',
			'sort'      => 'asc',
			'where'     => $where
		));

		if ( ! $images) return $entries;

		foreach ($entries['entries'] as &$entry) {

			$entry['images'] = $this->get_images($entry['id']);

		}

		return $entries;
	}

	public function get_images($id)
	{
		$images = $this->streams->entries->get_entries(array(
			'stream'    => 'gallery_images',
			'namespace' => 'galleries',
			'where'     => "gallery = '{$id}'",
			'order_by'  => 'ordering_count',
			'sort'      => 'asc'
		));

		return $images['entries'];
	}

	public function delete($id)
	{
		$gallery = $this->get($id);

		foreach ($gallery->images as $image) $this->images_m->delete($image['id']);

		Files::delete_folder($gallery->folder['id']);

		return $this->db->where('id', $id)->delete('galleries');
	}

	public function id_from_slug($slug)
	{
		$gallery = $this->db->select('id')->where('slug', $slug)->get('galleries');

		if ($gallery->num_rows()) return $gallery->row()->id;

		return false;
	}
}