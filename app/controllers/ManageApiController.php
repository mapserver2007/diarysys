<?php
namespace Blog\Manage;

use WebStream\Core\CoreController;
use WebStream\Annotation\Inject;
use WebStream\Annotation\Header;
use WebStream\Annotation\Filter;
use WebStream\Annotation\Template;
use WebStream\Annotation\Validate;
use WebStream\Module\Cache;

/**
 * ManageApiController
 */
class ManageApiController extends CoreController
{
    private $cacheId;

    /**
     * @Inject
     * @Filter(type="before", only={"entryCacheRead", "entryCacheCreate"})
     */
    public function before()
    {
        $this->cacheId = "entry_temporary_cache";
    }

    /**
     * @Inject
     * @Header(contentType="html", allowMethod="GET")
     * @Template("entry_create_tags.tmpl")
     */
    public function entryCreateTags()
    {
    }

    /**
     * @Inject
     * @Header(contentType="html", allowMethod="GET")
     * @Template("entry_create_category.tmpl")
     */
    public function entryCreateCategory()
    {
    }

    /**
     * @Inject
     * @Header(contentType="html", allowMethod="GET")
     * @Validate(key="p", rule="page", method="get")
     * @Validate(key="num", rule="range[1..100]", method="get")
     * @Template("entry_create_image.tmpl")
     */
    public function loadUploadImage()
    {
        $page = $this->request->get("p") ?: 1;
        $num = $this->request->get("num");
        $this->ManageApi->pathInfo = $this->request->getBaseURL() . $this->request->getPathInfo();
        $this->ManageApi->loadImage($page, $num);
    }

    /**
     * @Inject
     * @Header(contentType="json", allowMethod="GET")
     */
    public function entryCacheRead()
    {
        $cache = new Cache();
        echo json_encode(["data" => $cache->get($this->cacheId)]);
    }

    /**
     * @Inject
     * @Header(contentType="json", allowMethod="POST")
     */
    public function entryCacheCreate()
    {
        $cache = new Cache();
        echo json_encode(["result" => $cache->save(
            $this->cacheId,
            $this->request->post(),
            86400,
            true
        )]);
    }
}
