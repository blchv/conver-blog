<?php
/*
 * @author    Ivan Belchev <ivan@imbelchev.com>
 * @copyright Copyright (c) 2022 MIT (see LICENSE.md)
 * @link      https://imbelchev.com
 */

namespace Convert\Blog\Setup\Patch\Data;


use Convert\Blog\Api\Data\PostInterface;
use Convert\Blog\Api\PostRepositoryInterface;
use Convert\Blog\Model\ResourceModel\Post\CollectionFactory;
use Convert\Blog\Model\PostFactory;
use Exception;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

/**
 * Class InstallSamplePostData
 * @package Convert\Blog\Setup\Patch\Data
 */
class InstallSamplePostData implements DataPatchInterface, PatchRevertableInterface, PatchVersionInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var PostFactory
     */
    private $postFactory;
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @param CollectionFactory $collectionFactory
     * @param PostFactory $postFactory
     * @param PostRepositoryInterface $postRepository
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        PostFactory $postFactory,
        PostRepositoryInterface $postRepository,
        DataObjectHelper $dataObjectHelper
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->postFactory = $postFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->postRepository = $postRepository;
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }

    private function getSamplePostData(): array
    {
        return [
            [
                PostInterface::URL_KEY => 'cheeseburger-just-sandwich-wish-fulfilled',
                PostInterface::IS_DRAFT => false,
                PostInterface::AUTHOR => 'Convert',
                PostInterface::TITLE => 'A cheeseburger is more than just a sandwich, it is a wish fulfilled.',
                PostInterface::CONTENT => "<h2 id=\"habitant-placerat-tortor-mauris-dictumst-arcu-proin\">Habitant placerat tortor mauris dictumst arcu proin</h2> <p>Lorem ipsum dolor sit amet consectetur adipiscing elit, ultricies dis ante sociosqu eleifend sollicitudin torquent, libero cum aptent cursus eros pharetra. Semper laoreet ac pretium taciti in ligula commodo posuere nunc turpis gravida fusce elementum, feugiat nostra netus torquent hendrerit aliquam nam non mi ante lectus. </p><img src=\"https://picsum.photos/900/600?random=1\" alt=\"Random image 1\"><p>Suscipit proin natoque sagittis facilisi porta class auctor, neque primis gravida consequat egestas hac suspendisse a, vehicula urna nulla mattis massa velit. Dui sollicitudin habitasse et faucibus porttitor sapien praesent, mauris eleifend vitae proin mattis vivamus cum, pulvinar leo posuere netus mus cubilia. </p><h2 id=\"laoreet-aliquam-pulvinar-eros-posue\">Laoreet aliquam pulvinar eros posue</h2><img src=\"https://picsum.photos/900/600?random=2\" alt=\"Random image 2\"> <ul> <li> <p>Feugiat duis fermentum donec integer interdum, volutpat vivamus placerat.</p></li><li> <p>Urna interdum natoque torquent sagittis justo, leo himenaeos quis donec.</p></li><li> <p>Gravida sem nisi etiam a blandit parturient, vehicula curae diam nibh.</p></li><li> <p>Phasellus tempor fringilla vulputate ullamcorper blandit, pellentesque himenaeos litora potenti.</p></li></ul> <p>Lacinia duis aliquet neque viverra lobortis purus aptent class turpis platea blandit, feugiat magna luctus cursus quis rhoncus bibendum gravida cubilia. </p>"
            ],
            [
                PostInterface::URL_KEY => 'protein-iron-calcium-nutritional',
                PostInterface::IS_DRAFT => true,
                PostInterface::AUTHOR => 'Convert',
                PostInterface::TITLE => 'Protein, iron, and calcium are some of the nutritional',
                PostInterface::CONTENT => "<h2 id=\"nisl-nisi-class-montes-egestas-pulvinar-non-penatibus-ridiculu\">Nisl nisi class montes egestas pulvinar non penatibus ridiculu</h2><img src=\"https://picsum.photos/900/600?random=1\" alt=\"Random image 1\"><p>Lorem ipsum dolor sit amet consectetur adipiscing elit magnis, leo est vestibulum vel libero eget senectus lobortis, dis viverra neque elementum aenean id fringilla. Iaculis diam phasellus maecenas mollis odio turpis eros tincidunt egestas varius viverra risus purus, bibendum velit gravida nascetur fringilla habitant aliquam hac dictumst imperdiet quam semper. </p><img src=\"https://picsum.photos/900/600?random=2\" alt=\"Random image 2\"><p>Auctor interdum maecenas eros netus at pretium vestibulum inceptos sollicitudin phasellus, tellus mi aenean taciti augue turpis volutpat litora imperdiet nascetur, velit tortor fusce facilisis facilisi mattis senectus sagittis libero. </p><h2 id=\"vulputate-sapien-tempus-dui-pa\">Vulputate sapien tempus dui pa</h2><ul> <li> <p>Nullam natoque arcu mauris primis, erat pellentesque luctus.</p></li><li> <p>Curae placerat lectus nibh hendrerit, laoreet diam iaculis.</p></li><li> <p>Faucibus conubia ut duis lacinia, eget diam.</p></li><li> <p>Netus pretium taciti himenaeos habitasse, a nullam cras.</p></li></ul>"
            ],
            [
                PostInterface::URL_KEY => 'salad-essentially-food-rabbits',
                PostInterface::IS_DRAFT => false,
                PostInterface::AUTHOR => 'Convert',
                PostInterface::TITLE => 'Salad is essentially food for rabbits',
                PostInterface::CONTENT => "<h2 id=\"sem-risus-rutrum-pharetra-viverra-tortor-quisque-mi-dignissim-in-per-luctus\">Sem risus rutrum pharetra viverra tortor quisque mi dignissim in per luctus</h2><p>Lorem ipsum dolor sit amet consectetur adipiscing elit tempor dignissim curabitur rutrum ut convallis sollicitudin, taciti vitae vivamus facilisis proin non morbi per venenatis enim primis tortor senectus. Fringilla porttitor arcu enim semper torquent aliquam, litora pellentesque in congue tempus potenti curae, vel proin faucibus ante curabitur. Iaculis congue mus morbi dictumst taciti, lectus commodo habitasse eu praesent, fringilla mauris aliquam feugiat. </p><img src=\"https://picsum.photos/900/600?random=1\" alt=\"Random image 1\"><p>Laoreet ac rutrum accumsan himenaeos consequat cursus senectus, turpis quisque in auctor nostra nulla, massa phasellus posuere ridiculus mi ullamcorper. Habitant maecenas at aliquet porta egestas magnis metus ut, pellentesque venenatis pharetra ullamcorper semper sagittis duis, nostra facilisi ligula ultricies rhoncus et dis. </p><h2 id=\"eleifend-ut-praesent-consequat-conva\">Eleifend ut praesent consequat conva</h2><ul> <li> <p>Libero ridiculus proin vulputate id ligula, conubia luctus tellus justo.</p></li><li> <p>Orci nascetur dapibus ullamcorper urna est, consequat gravida penatibus pretium.</p></li><li> <p>Fusce inceptos pellentesque suscipit torquent, dictum dui diam.</p></li><li> <p>Porta vulputate urna ullamcorper luctus eu senectus, turpis ornare accumsan dictumst.</p></li></ul><img src=\"https://picsum.photos/900/600?random=2\" alt=\"Random image 2\"><p>Arcu vestibulum a tempor litora mattis aliquet dis curabitur blandit phasellus duis porta molestie ante, fames volutpat tempus nisi auctor dictumst inceptos eget taciti nunc placerat nascetur. Turpis aptent penatibus conubia augue in cursus primis porttitor vivamus nam nec lacinia enim, dui mus varius lacus per suscipit cras nascetur metus litora tempus risus. </p>"
            ]
        ];
    }

    /**
     * @return void
     * @throws Exception
     */
    public function apply()
    {
        foreach($this->getSamplePostData() as $postData) {
            $post = $this->postFactory->create();
            $this->dataObjectHelper->populateWithArray($post, $postData, PostInterface::class);
            $this->postRepository->save($post);
        }
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    public function revert()
    {
        $postCollection = $this->collectionFactory->create();

        forEach($this->getSamplePostData() as $postData) {
            $postCollection->addFilter('title', $postData['title'], 'or');
        }

        forEach($postCollection as $post) {
            echo "Deleting dummy post with ID: {$post->getId()}... ";
            $this->postRepository->delete($post);
            echo "Done \n";
        }
    }

    /**
     * @return string
     */
    public static function getVersion(): string
    {
        return '0.0.1';
    }
}
