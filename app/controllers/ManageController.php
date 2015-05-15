<?php
namespace Blog\Manage;

use WebStream\Core\CoreController;
use WebStream\Annotation\Inject;
use WebStream\Annotation\Filter;
use WebStream\Annotation\Validate;
use WebStream\Annotation\Header;
use WebStream\Annotation\Template;
use WebStream\Annotation\CsrfProtection;
use WebStream\Annotation\ExceptionHandler;

/**
 * ManageController
 */
class ManageController extends CoreController
{
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
    public function index()
    {
        $this->Manage->setViewModel([
            'currentPage' => $this->page,
            'maxPerPage' => $this->num,
            'pathInfo' => $this->request->getBaseURL() . $this->request->getPathInfo(),
            'query' => $this->request->get("q"),
            'entryCount' => $this->Manage->entryCount(['query' => $this->request->get("q")]),
            'entryList' => $this->Manage->entryList(['num' => $this->num, 'page' => $this->page, 'query' => $this->request->get("q")])
        ]);
    }

    /**
     * @Inject
     * @Header(contentType="html", allowMethod="GET")
     * @Template("entry_create.tmpl")
     */
    public function entryCreate()
    {

    }

    /**
     * @Inject
     * @Header(contentType="html", allowMethod="GET")
     * @Template("entry_create_preview.tmpl")
     */
    public function entryCreatePreview()
    {

    }

    public function entryEdit()
    {

    }

    /**
     * @Inject
     * @CsrfProtection
     * @Validate(key="entry_id", rule="required")
     * @Validate(key="entry_id", rule="number")
     * @Header(contentType="html", allowMethod="POST")
     * @Template("entry_delete_confirm.tmpl")
     */
    public function entryDeleteConfirm()
    {
        $this->Manage->entryById($this->request->post("entry_id"));
    }

    /**
     * @Inject
     * @CsrfProtection
     * @Validate(key="entry_id", rule="required")
     * @Validate(key="entry_id", rule="number")
     * @Header(contentType="html", allowMethod="POST")
     * @Template("entry_delete_complete.tmpl")
     */
    public function entryDeleteComplete()
    {
        $this->Manage->entryDeleteById(['entry_id' => $this->request->post("entry_id")]);
    }

    /**
     * @Inject
     * @Header(contentType="html", allowMethod="GET")
     * @Template("entry_create_image.tmpl")
     */
    public function entryCreateImage()
    {
        $this->Manage->setViewModel(['imageNum' => 10]);
    }

    /**
     * @Inject
     * @ExceptionHandler("WebStream\Exception\ApplicationException")
     */
    public function error($params)
    {
        // TODO セッション情報を削除
        var_dump($params);
    }
}
