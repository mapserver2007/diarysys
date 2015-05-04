<?php
namespace WebStream\Module;

use WebStream\Exception\SystemException;

/**
 * Utility
 * @author Ryuichi Tanaka
 * @since 2013/09/06
 * @version 0.4
 */
trait Utility
{
    /**
     * CSRF�ȩ`���󥭩`��ȴ����
     * @return string CSRF�ȩ`���󥭩`
     */
    public function getCsrfTokenKey()
    {
        return "__CSRF_TOKEN__";
    }

    /**
     * CoreHelper#async��ʹ�ä���ID��ȴ����
     * @return string DOMID
     */
    public function getAsyncDomId()
    {
        return $this->getRandomstring(32);
    }

    /**
     * View���Є���Model��������ȴ����
     * @return string Model������
     */
    public function getModelVariableName()
    {
        return "model";
    }

    /**
     * View���Є���Helper��������ȴ����
     * @return string Helper������
     */
    public function getHelperVariableName()
    {
        return "helper";
    }

    /**
     * �ץ������ȥ�`�ȥե���������ȴ����
     * @return string �ץ������ȥ�`�ȥե�������
     */
    private function getProjectFileName()
    {
        return ".projectroot";
    }

    /**
     * �ץ������ȥǥ��쥯�ȥ�ν~���ѥ��򷵤�
     * @return string �ץ������ȥǥ��쥯�ȥ�ν~���ѥ�
     */
    public function getRoot()
    {
        // ��λ�A�Ӥ��{�ꡢ.projectroot�ե������Ҋ�Ĥ���
        $targetPath = realpath(dirname(__FILE__));
        $isProjectRoot = false;

        while (!$isProjectRoot) {
            if (file_exists($targetPath . DIRECTORY_SEPARATOR . $this->getProjectFileName())) {
                $isProjectRoot = true;
            } else {
                if (preg_match("/(.*)\//", $targetPath, $matches)) {
                    $targetPath = $matches[1];
                    if (!is_dir($targetPath)) {
                        break;
                    }
                }
            }
        }

        if (!$isProjectRoot) {
            throw new SystemException("'.projectroot' file must be put in directly under the project directory.");
        }

        return $targetPath;
    }

    /**
     * �ƥ��ȭh���ǤΥ��ץꥱ�`������`�ȥѥ���ȴ����(�����Ǥ�ʹ�ä��ʤ�)
     * @return string ���ץꥱ�`������`�ȥѥ�
     */
    public function getTestApplicationRoot()
    {
        return $this->getRoot() . "/core/WebStream/Test/Sample";
    }

    /**
     * �ƥ��ȭh���ǤΥ��ץꥱ�`�����ǥ��쥯�ȥ�ѥ���ȴ����(�����Ǥ�ʹ�ä��ʤ�)
     * @return string ���ץꥱ�`������`�ȥѥ�
     */
    public function getTestApplicationDir()
    {
        return "core/WebStream/Test/Sample/app";
    }

    /**
     * �ե�����������ƥ�`����ȴ����
     * @param string �ǥ��쥯�ȥ�ѥ�
     * @return object ���ƥ�`��
     */
    public function getFileSearchIterator($path)
    {
        return new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::LEAVES_ONLY,
            \RecursiveIteratorIterator::CATCH_GET_CHILD // for Permission deny
        );
    }

    /**
     * ָ�������ե��������ǰ���g��ȡ�ä���
     * @param string �ե�����ѥ�
     * @param string ���ǥ��쥯�ȥ�ѥ�
     * @return string ��ǰ���g
     */
    public function getNamespace($filepath, $baseDir = null)
    {
        if (file_exists($filepath)) {
            $resource = fopen($filepath, "r");
            while (false !== ($line = fgets($resource))) {
                if (preg_match("/^namespace\s(.*);$/", $line, $matches)) {
                    $namespace = $matches[1];
                    if (substr($namespace, 0) !== '\\') {
                        $namespace = '\\' . $namespace;
                    }

                    return $namespace;
                }
            }
            fclose($resource);
        }

        return null;
    }

    /**
     * �O���ե������ѩ`������
     * @param string �ץ������ȥ�`�Ȥ���������ѥ�
     * @return hash �O�����
     */
    public function parseConfig($filepath)
    {
        // ��Ҏ�������~���ѥ�
        $realpath = $this->getRoot() . DIRECTORY_SEPARATOR . $filepath;

        return file_exists($realpath) ? parse_ini_file($realpath) : null;
    }

    /**
     * ������������Ф����ɤ��Ʒ�ȴ����
     * @param int ���ɤ���������(ʡ�ԕr��10����)
     * @return string ������������
     */
    public function getRandomstring($length = 10)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_";
        mt_srand();
        $random_str = "";
        for ($i = 0; $i < $length; $i++) {
            $random_str .= $chars{mt_rand(0, strlen($chars) - 1)};
        }

        return $random_str;
    }

    /**
     * һ�r�ǥ��쥯�ȥ�ѥ���ȴ����
     * @return string һ�r�ǥ��쥯�ȥ�ѥ�
     */
    public function getTemporaryDirectory()
    {
        return PHP_OS === "WIN32" || PHP_OS === "WINNT" ? "C:\\Windows\\Temp" : "/tmp";
    }

    /**
     * �����륱�`�������Ф򥹥ͩ`�����`�������Ф��ÓQ����
     * @param string �����륱�`��������
     * @return string ���ͩ`�����`��������
     */
    public function camel2snake($str)
    {
        $str = preg_replace_callback('/([A-Z])/', function ($matches) {
            return '_' . lcfirst($matches[1]);
        }, $str);

        return preg_replace('/^_/', '', $str);
    }

    /**
     * ���ͩ`�����`�������Ф򥢥åѩ`�����륱�`�����ÓQ����
     * @param string ���ͩ`�����`��������
     * @return string ���åѩ`�����륱�`��������
     */
    public function snake2ucamel($str)
    {
        $str = ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($matches) {
            return ucfirst($matches[1]);
        }, $str));

        return $str;
    }

    /**
     * ���ͩ`�����`�������Ф��`��`�����륱�`�����ÓQ����
     * @param string ���ͩ`�����`��������
     * @return string ��`��`�����륱�`��������
     */
    public function snake2lcamel($str)
    {
        return lcfirst(self::snake2ucamel($str));
    }

    /**
     * XML���֥������Ȥ����Фˉ�Q����
     * @param object XML���֥�������
     * @return Hash ����/�ϥå���ǩ`��
     */
    public function xml2array($xml)
    {
        $result = array();
        if (is_object($xml)) {
            $list = get_object_vars($xml);
            while (list($k, $v) = each($list)) {
                $result[$k] = Utility::xml2array($v);
            }
        } elseif (is_array($xml)) {
            while (list($k, $v) = each($xml)) {
                $result[$k] = Utility::xml2array($v);
            }
        } else {
            $result = $xml;
        }

        return $result;
    }

    /**
     * �ե����뤫��mime�����פ�ȴ����
     * @param string �ե����륿����
     * @return string mime������
     */
    public function getMimeType($type)
    {
        switch ($type) {
            case "txt":
                return "text/plain";
            case "jpeg":
            case "jpg":
                return "image/jpeg";
            case "gif":
                return "image/gif";
            case "png":
                return "image/png";
            case "tiff":
                return "image/tiff";
            case "bmp":
                return "image/bmp";
            case "xml":
            case "rss":
            case "rdf":
            case "atom":
                 return "application/xml";
            case "html":
            case "htm":
                return "text/html";
            case "css":
                return "text/css";
            case "js":
            case "jsonp":
                return "text/javascript";
            case "json":
                return "application/json";
            case "pdf":
                return "application/pdf";
            default:
                return "application/octet-stream";
        }
    }

    /**
     * �ǩ`���ΥХ����L��ȴ����
     * @param string ������
     * @return string �Х����L
     */
    public function bytelen($data)
    {
        return strlen(bin2hex($data)) / 2;
    }

    /**
     * �ǩ`���򥷥ꥢ�饤�������ƥƥ����ȥǩ`���˥��󥳩`�ɤ���
     * @param object ����ǩ`��
     * @return string ���󥳩`�ɤ����ǩ`��
     */
    public function encode($data)
    {
        return base64_encode(serialize($data));
    }

    /**
     * �ǩ`����ǥ��ꥢ�饤��������Ԫ�Υǩ`����ǥ��`�ɤ���
     * @param string ���󥳩`�ɜg�ߥǩ`��
     * @return object �ǥ��`�ɤ����ǩ`��
     */
    public function decode($data)
    {
        return unserialize(base64_decode($data));
    }

    /**
     * Ҫ�ؤ����ڤ��뤫�ɤ���
     * @param array ������������
     * @param mixed ������
     * @return bool ���ڤ����true
     */
    public function inArray($target, $list)
    {
        $type = gettype($target);
        switch ($type) {
            case "string":
            case "integer":
                return array_key_exists($target, array_flip($list));
            default:
                // ��������Έ��ϡ�in_array��ʹ�ä���
                return in_array($target, $list, true);
        }
    }

    /**
     * CoreHelper#async��ʹ�ä��륳�`�ɤ�ȴ����
     * @param string URL
     * @param string CSS���饹��
     * @return string ���`��
     */
    public function asyncHelperCode($url, $id)
    {
        return <<< JSCODE
(function (c,b) {var a;a=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");a.onreadystatechange=function () {4==a.readyState&&200==a.status&&(document.getElementById(b).outerHTML=a.responseText)};a.open("GET",c,!0);a.send()})("$url","$id");
JSCODE;
    }
}
