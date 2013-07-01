<?php defined('BASEPATH') or exit('No direct script access allowed');

class Images_m extends MY_Model
{
	public function delete($id)
	{
		$image = $this->db->where('id', $id)->get('gallery_images')->row();

		if ( ! $image) return false;

		Files::delete_file($image->file);

		return $this->db->where('id', $id)->delete('gallery_images');
	}
}