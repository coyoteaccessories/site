<?php
namespace Zemez\Blog\Model\Post\Source;

use Zemez\Blog\Model\Post;

class IsVisible implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Zemez\Blog\Model\Post
     */
    protected $post;

    /**
     * Constructor
     *
     * @param \Zemez\Blog\Model\Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->post->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
