<?php

namespace WPPluginName\CPT;

/**
 * Class AbstractCPT
 * @package WPPluginName\CPT
 */
abstract class AbstractCPT
{
    protected $postID;
    protected $cptName;
    protected $cptFormalName;
    protected $taxName;

    private $post;

    abstract function setCPTName();
    abstract function setCPTFormalName();

    /**
     * AbstractCPT constructor.
     * @param array|null $data
     */
    public function __construct(?array $data)
    {

    }

    public static function registerActions(): void
    {
        add_action('init', [__CLASS__, 'createCPT']);
        add_action('init', [__CLASS__, 'registerTax']);
        add_action('add_meta_boxes',[__CLASS__, 'create_meta_box']);
    }

    /**
     * @return \WP_Post
     */
    public function getPost(): \WP_Post
    {
        if (!$this->post) {
            $this->post = get_post($this->getPostID());
        }
        return $this->post;
    }

    /**
     * @return mixed
     */
    public function getPostID()
    {
        return $this->postID;
    }

    /**
     * @param mixed $postID
     * @return AbstractCPT
     */
    public function setPostID($postID)
    {
        $this->postID = $postID;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxName()
    {
        return $this->taxName;
    }

    /**
     * @param mixed $taxName
     * @return AbstractCPT
     */
    public function setTaxName($taxName)
    {
        $this->taxName = $taxName;
        return $this;
    }
}