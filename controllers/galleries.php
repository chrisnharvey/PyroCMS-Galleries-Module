<?php defined('BASEPATH') or exit('No direct script access allowed');

class Galleries extends Public_Controller
{
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
		$this->template->build('index');
	}

	public function gallery($slug)
	{

	}

	public function xml()
	{
		$galleries = $this->galleries_m->get_all(true);

		$xml = new SimpleXMLElement('<xml/>');

		foreach ($galleries['entries'] as $gallery)
		{
			$node = $xml->addChild('item');

			$node->addChild('name', $gallery['name']);

			$images = $node->addChild('images');

			foreach ($gallery['images'] as $image) {
				$item = $images->addChild('image');

				$item->addChild('name', $image['name']);
				$item->addChild('caption', $image['caption']);
				$item->addChild('thumb', site_url("files/thumb/{$image['file']}"));
				$item->addChild('large', site_url("files/large/{$image['file']}"));
			}
		}

		$this->output
			->set_content_type('application/xml')
			->set_output($xml->asXML());
	}
}