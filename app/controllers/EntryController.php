<?php
namespace Blog\Entry;

use WebStream\Annotation\Inject;
use WebStream\Annotation\Filter;
use WebStream\Annotation\Validate;
use WebStream\Annotation\Header;
use WebStream\Annotation\Template;
use WebStream\Exception\Extend\ResourceNotFoundException;

/**
 * EntryController
 */
class EntryController extends ApplicationController
{
    /**
     * @var int 表示件数
     */
    private $num;

    /**
     * @var int ページ番号
     */
    private $page;

    /**
     * @Inject
     * @Filter(type="before")
     */
    public function initialize()
    {
        $this->num = 10;
        $this->page = $this->request->get("p") ? intval($this->request->get("p")) : 1;
        $this->Entry->setPathInfo($this->request->getBaseURL() . $this->request->getPathInfo());
    }

    /**
     * @Inject
     * @Validate(key="p", rule="page", method="get")
     * @Header(contentType="html", allowMethod="GET")
     * @Template("index.tmpl")
     */
    public function entry()
    {
        $this->entryList();
    }

    /**
     * @Inject
     * @Validate(key="p", rule="page", method="get")
     * @Header(contentType="html", allowMethod="GET")
     * @Template("index.tmpl")
     */
    public function entryById(array $params)
    {
        $this->entryList([
            'entry_id' => intval($params["entry_id"])
        ]);
    }

    /**
     * @Inject
     * @Validate(key="p", rule="page", method="get")
     * @Header(contentType="html", allowMethod="GET")
     * @Template("index.tmpl")
     */
    public function entryByTag(array $params)
    {
        $this->entryList([
            'tag_name' => $params["tag_name"]
        ]);
    }

    /**
     * @Inject
     * @Validate(key="p", rule="page", method="get")
     * @Header(contentType="html", allowMethod="GET")
     * @Template("index.tmpl")
     */
    public function entryByCategory(array $params)
    {
        $this->entryList([
            'category_name' => $params["category_name"]
        ]);
    }

    /**
     * @Inject
     * @Validate(key="p", rule="page", method="get")
     * @Header(contentType="html", allowMethod="GET")
     * @Template("index.tmpl")
     */
    public function entryByMonth(array $params)
    {
        $list = str_split($params["yyyymm"], 4);

        $yyyymm = $params["yyyymm"];
        if (!preg_match('/^[0-9]{6}$/', $yyyymm)) {
            throw new ResourceNotFoundException();
        }
        $list = str_split($yyyymm, 4);

        $this->entryList([
            'created_at' => $list[0] . "-" . $list[1]
        ]);
    }

    private function entryList($params)
    {
        $this->Entry->entryList(array_merge($params, ['num' => $this->num, 'page' => $this->page]));
        $this->Entry->entryCount($params);
    }
}
