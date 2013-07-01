<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends Admin_Controller
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

	public function index()
	{
		$galleries = $this->galleries_m->get_all(true);

		$this->template
			->append_css('module::galleries.css')
			->append_js('module::jquery.iscrubber.min.js')
			->append_js('module::galleries.js')
			->build('admin/index', $galleries);
	}

	public function create()
	{
		$stream = $this->streams->streams->get_stream('galleries', 'galleries');
		$data['fields'] = $this->fields->build_form($stream, 'new', $this->input->post(), false, false, array('folder'), array('return' => false));

		if (is_numeric($data['fields'])) {
			$folder = Files::create_folder($this->settings->galleries_folder, $this->input->post('name'));

			$this->db->update('galleries', array(
				'folder' => $folder['data']['id']
			), array(
				'id' => $data['fields']
			));

			redirect("admin/galleries/edit/{$data['fields']}#images");
		}

		$this->template->build('admin/create', $data);
	}

	public function edit($id)
	{
		$stream = $this->streams->streams->get_stream('galleries', 'galleries');
		$row = $this->row_m->get_row($id, $stream, false);

		foreach ($this->input->post() as $key => $value) $row->$key = $value;

		$data['id'] = $id;
		$data['name'] = $row->name;
		$data['fields'] = $this->fields->build_form($stream, 'edit', $row, false, false, array('folder'), array('return' => false));

		if (is_numeric($data['fields'])) {
			Files::rename_folder($row->folder, $row->name);

			redirect('admin/galleries');
		}

		$data['images'] = $this->galleries_m->get_images($id);

		$this->template
			->append_js('module::jquery.filedrop.js')
			->append_js('module::upload.js')
			->append_js('module::edit.js')
			->append_css('module::edit.css')
			->build('admin/edit', $data);
	}

	public function delete($id)
	{
		$this->galleries_m->delete($id);

		redirect('admin/galleries');
	}

	public function live($id)
	{
		$this->db->set('status', 'live')->where('id', $id)->update('galleries');

		redirect('admin/galleries');
	}

	public function draft($id)
	{
		$this->db->set('status', 'draft')->where('id', $id)->update('galleries');

		redirect('admin/galleries');
	}

	public function upload($id)
	{
		// Get the folder
		$gallery = $this->db->select('folder')->where('id', $id)->get('galleries');

        // Check for folder
        if ($gallery->num_rows()) {

            // Upload it
            $data = Files::upload($gallery->row()->folder);

            // Success
            if ($data['status'] == true) {

            	$this->streams->entries->insert_entry(array(

            		'name'    => $data['data']['name'],
            		'slug'    => url_title($data['data']['name'], 'dash'),
            		'file'    => $data['data']['id'],
            		'gallery' => $id

            	), 'gallery_images', 'galleries');

            	echo json_encode(array('status' => $data['status'], 'message' => $data['message']));
            	exit;
                
            }
        }

        // Seems it was unsuccessful
        $this->template->build_json(array('status' => false, 'message' => 'Error uploading image'));
	}

	public function reorder()
	{
		$order = explode(',', $this->input->post('order'));

		$i = 1;

		foreach ($order as $id) {

			// Update the ordering_count
			$this->db->set('ordering_count', $i)->where('id', $id)->update('galleries');

			$i++;

		}
	}
}