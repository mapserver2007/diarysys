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
    }

    /**
     * @Inject
     * @Validate(key="p", rule="page", method="get")
     * @Header(contentType="html", allowMethod="GET")
     * @Template("index.tmpl")
     */
    public function entry()
    {
        $this->Entry->entryList([
            'num' => $this->num,
            'page' => $this->page
        ]);
    }

    /**
     * @Inject
     * @Validate(key="p", rule="page", method="get")
     * @Header(contentType="html", allowMethod="GET")
     * @Template("index.tmpl")
     */
    public function entryById(array $params)
    {
        $this->Entry->entryList([
            'num' => $this->num,
            'page' => $this->page,
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
        $this->Entry->entryList([
            'num' => $this->num,
            'page' => $this->page,
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
        $this->Entry->entryList([
            'num' => $this->num,
            'page' => $this->page,
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

        $this->Entry->entryList([
            'num' => $this->num,
            'page' => $this->page,
            'created_at' => $list[0] . "-" . $list[1]
        ]);
    }
}
