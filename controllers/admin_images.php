<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_images extends Admin_Controller
{
	public $section = 'galleries';

	public function __construct()
	{
		parent::__construct();

		$this->load->driver('Streams');
		$this->load->library('files/files');
		$this->lang->load('galleries');
		$this->load->model(array('galleries_m', 'images_m'));
	}
	
	public function edit($id)
	{
		$stream = $this->streams->streams->get_stream('gallery_images', 'galleries');
		$row = $this->row_m->get_row($id, $stream, false);

		foreach ($this->input->post() as $key => $value) $row->$key = $value;

		$data['id'] = $id;
		$data['fields'] = $this->fields->build_form($stream, 'edit', $row, FALSE, FALSE, array('file', 'gallery'), array('return' => false));

		if (is_numeric($data['fields'])) {
			
			Files::rename_file($row->file, $row->name);

			$this->template->build_json($row);
			
		} else {
			
			$this->template
				->set_layout(false)
				->build('admin/images/edit', $data);
				
		}
	}

	public function delete($id)
	{
		if ($this->images_m->delete($id)) {

			redirect("admin/galleries/edit/{$image->gallery}#images");

		} else {

			show_404();

		}
	}

	public function reorder()
	{
		$order = explode(',', $this->input->post('order'));

		$i = 1;

		foreach ($order as $id) {

			// Update the ordering_count
			$this->db->set('ordering_count', $i)->where('id', $id)->update('gallery_images');

			$i++;

		}
	}
}