<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Galleries extends Module
{
    public $version = '1.0.0';

    public function __construct()
    {
        parent::__construct();

        // Add our field type path
        $core_path = defined('PYROPATH') ? PYROPATH : APPPATH;

        if (is_dir(SHARED_ADDONPATH.'modules/galleries/field_types')) {
            $this->type->addon_paths['galleries'] = SHARED_ADDONPATH.'modules/galleries/field_types/';
        } elseif (is_dir($core_path.'modules/galleries/field_types')) {
            $this->type->addon_paths['galleries'] = $core_path.'modules/galleries/field_types/';
        } else {
            $this->type->addon_paths['galleries'] = ADDONPATH.'modules/galleries/field_types/';
        }

        $this->type->gather_types();
    }

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Galleries'
            ),
            'description' => array(
                'en' => 'Setup multiple photo galleries',
            ),
            'frontend' => TRUE,
            'backend' => TRUE,
            'menu' => 'content',
            'sections' => array(
                'galleries' => array(
                    'name' => 'galleries',
                    'uri' => 'admin/galleries',
                    'shortcuts' => array(
                        array(
                            'name'  => 'galleries:create',
                            'uri'   => 'admin/galleries/create',
                            'class' => 'add'
                        )
                    )
                )
            ),
        );
    }

    public function install()
    {
        $this->load->driver('Streams');
        $this->load->library('files/files');

        // Create the default folder
        $folder = Files::create_folder(0, 'Galleries');

        // Add the settings
        $this->db->insert('settings', array(
            'slug' => 'galleries_folder',
            'title' => 'Galleries Default Folder',
            'description' => 'The toplevel galleries folder',
            'value' => $folder['data']['id'],
            'type' => '',
            'default' => '',
            'options' => '',
            'is_required' => 1,
            'is_gui' => 0,
            'module' => 'galleries'
        ));

        // Add the streams
        $this->streams->streams->add_stream('Galleries', 'galleries', 'galleries');
        $this->streams->streams->add_stream('Gallery Images', 'gallery_images', 'galleries');

        // Add the fields to the galleries
        $this->streams->fields->add_fields(array(
            array(
                'name'         => 'Name',
                'slug'         => 'name',
                'namespace'    => 'galleries',
                'type'         => 'text',
                'extra'        => array('max_length' => 200),
                'assign'       => 'galleries',
                'title_column' => true,
                'required'     => true
            ),

            array(
                'name'         => 'Slug',
                'slug'         => 'slug',
                'namespace'    => 'galleries',
                'type'         => 'slug',
                'extra'        => array('space_type' => '-', 'slug_field' => 'name'),
                'assign'       => 'galleries',
                'required'     => true,
                'unique'       => true
            ),

            array(
                'name'         => 'Status',
                'slug'         => 'status',
                'namespace'    => 'galleries',
                'type'         => 'choice',
                'extra'        => array(
                    'choice_data'   => "live : Live\ndraft : Draft",
                    'choice_type'   => 'dropdown',
                    'default_value' => 'draft'
                ),
                'assign'       => 'galleries',
                'required'     => true
            ),

            array(
                'name'         => 'Folder',
                'slug'         => 'folder',
                'namespace'    => 'galleries',
                'type'         => 'file_folder',
                'assign'       => 'galleries',
                'required'     => true,
                'unique'       => true
            ),
        ));

        // Reuse some fields on the images stream
        $this->streams->fields->assign_field('galleries', 'gallery_images', 'name');

        // Get the galleries stream out so we can do a relationship on it
        $galleries = $this->streams->streams->get_stream('galleries', 'galleries');

        // Add the fields
        $this->streams->fields->add_fields(array(
            array(
                'name'         => 'Caption',
                'slug'         => 'caption',
                'namespace'    => 'galleries',
                'type'         => 'textarea',
                'assign'       => 'gallery_images'
            ),

            array(
                'name'         => 'File',
                'slug'         => 'file',
                'namespace'    => 'galleries',
                'type'         => 'text',
                'extra'        => array('max_length' => 15),
                'assign'       => 'gallery_images',
                'required'     => true,
                'unique'       => true
            ),

            array(
                'name'         => 'Gallery',
                'slug'         => 'gallery',
                'namespace'    => 'galleries',
                'type'         => 'relationship',
                'extra'        => array('choose_stream' => $galleries->id),
                'assign'       => 'gallery_images',
                'required'     => true
            ),
        ));

        return true;
    }

    public function uninstall()
    {
        $this->load->driver('Streams');

        // Remove the streams namespace
        $this->streams->utilities->remove_namespace('galleries');

        // Delete settings
        $this->db->delete('settings', array(
            'slug' => 'galleries_folder',
            'module' => 'galleries'
        ));

        return true;
    }

    public function upgrade($old_version)
    {
        return true;
    }

}