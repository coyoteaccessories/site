<?php
namespace Zemez\Blog\Model\Comment\Source;

use Zemez\Blog\Model\Comment;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Zemez\Blog\Model\Comment
     */
    protected $comment;

    /**
     * Constructor
     *
     * @param \Zemez\Blog\Model\Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->comment->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
