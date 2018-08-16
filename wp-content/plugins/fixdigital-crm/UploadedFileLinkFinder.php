<?php
namespace WebGroupCrm;

/**
 * This class try to get link to uploaded file by form field with type 'file'
 * 
 *
 * @author satuskam
 */

class UploadedFileLinkFinder
{
    private $_mailer;
    private $_formType;
    private $_index;  // case for pojo, vfb and cf7 forms (one file for one field uploading)
    private $_indexes = [];  // elementor form case (multiple file for one field uplading)
    
    public function __construct(\PHPMailer $mailer)
    {
        $this->_mailer = $mailer;
        $this->_formType = $_POST['form_type_for_uco_crm_integration'];
        
    }
    
    
    /*
     *  For Pojo, VFB and CF7 form.
     */
    public function getLinkToUploadedFile($fieldName)
    {
        $link = '';
      
        $isFileUploaded = isset($_FILES[$fieldName]['tmp_name']) && $_FILES[$fieldName]['tmp_name'] ;
        if (!$isFileUploaded) return $link;
       
        $this->_assignUploadedFileIndex($fieldName);
     
        $link = $this->_getLink();

        return $link;
    }
    
    
    /*
     *  For Elementor form
     */
    public function getLinksToUploadedFilesByElementor($fieldName)
    {
        $links = [];
        $matches = array();
            
        $regex = '/^form_fields\[([^\s\[\]]+)\]/';
        preg_match($regex, $fieldName, $matches);
        
        if (empty($matches[1])) return $links;
        
        $key = $matches[1];

        $isFileUploaded = isset($_FILES['form_fields'][$key][0]['tmp_name']) && $_FILES['form_fields'][$key][0]['tmp_name'] ;
           
        if (!$isFileUploaded) return $links;

        $this->_assignUploadedByElementorFilesIndexes($key);

        $links = $this->_getLinksFromElementorForm();
        
        return $links;
    }
    
    
    private function _getLink()
    {
        $link = '';
        
        if ($this->_formType === 'pojo') {
            $link = $this->_getLinkFromPojoForm();
            
        } else if ($this->_formType === 'cf7') {
            $link = $this->_getLinkFromCf7Form();
            
        } else if ($this->_formType === 'vfb') {
            $link = $this->_getLinkFromVfbForm();
        }
        
        return $link;
    }
    
    
    private function _getLinkFromPojoForm()
    {
        $link = '';
        
        $regex = '@http[\S]+/wp-content/uploads[\S]*/pojo_forms/[\S]+@i';
        
        $match = array();
        $res = preg_match_all($regex, $this->_mailer->Body, $match);

        if ($res && !empty($match[0][$this->_index])) {
            $link = $match[0][$this->_index];
        }
       
        return $link;
    }
    
    
    private function _getLinksFromElementorForm()
    {
        $links = array();
        
        $regex = '@http[\S]+/wp-content/uploads[\S]*/elementor/forms/[^<>\s]+@i';
        
        $match = array();
        $res = preg_match_all($regex, $this->_mailer->Body, $match);

        if ($res && is_array($match[0])) {
            foreach ($match[0] as $idx => $l) {
                if (in_array($idx, $this->_indexes, true)) {
                    $links[] = $l;
                }
            }
        }
       
        return array_filter($links);
    }
    
    
    private function _getLinkFromCf7Form()
    {
        $link = '';
        
        $attachs = $this->_mailer->getAttachments();
       
        if (empty($attachs[$this->_index][0])) return $link;
        
        $attachPath = $attachs[$this->_index][0];
        
        $uploadDirData = wp_upload_dir();
        $uploadFileBaseDir = $uploadDirData['basedir'] . '/wpcf7_uploads/';
        $uploadFileBaseUrl = $uploadDirData['baseurl'] . '/wpcf7_uploads/';
        
        $r = "@[^/]*\.[^\.]+$@i";
        
        $matches = array();
        
        // copy the uploaded temprarily file to save it before it will be removed and get url to copied file.
        if (preg_match($r, $attachPath, $matches)) {
            if (!empty($matches[0])) {
                $newFileName = uniqid() . '_' . $matches[0];
                $newFilePath = $uploadFileBaseDir . $newFileName;
                
                $res = copy($attachPath, $newFilePath);
                if ($res) {
                    $link = $uploadFileBaseUrl . $newFileName;
                }
            }
        }
        
//        $regex = "@{$uploadFileBaseDir}[\S]+@i";
//        
//        if (preg_match($regex, $attachPath)) {
//            $link = str_replace($uploadFileBaseDir, $uploadFileBaseUrl, $attachPath);
//        }
        
        return $link;
    }
    
    
    private function _getLinkFromVfbForm()
    {
        $link = '';

        $regexForHtml = '@href="(http[\S]+/wp-content/uploads/[\S]+)"@i';
        $regexForPlainText = '@http[\S]+/wp-content/uploads/[\S]+@i';
        
        $matches = array();
        $res = preg_match_all($regexForHtml, $this->_mailer->Body, $matches);
        
        if ($res) {
            if (!empty($matches[1][$this->_index])) {
                $link = $matches[1][$this->_index];
            }
        } else {
            $res = preg_match_all($regexForPlainText, $this->_mailer->Body, $matches);
            if ($res && !empty($matches[0][$this->_index])) {
                $link = $matches[0][$this->_index];
            }
        }
        
        return $link;
    }
    
    
    private function _assignUploadedFileIndex($fieldName)
    {
        $idx = 0;

        foreach ($_FILES as $fName => $fData) {
            if (empty($_FILES[$fName]['tmp_name'])) continue;
            
            if ($fieldName === $fName) break;
            
            $idx++;
        }
        
        $this->_index =  $idx;
    }
    
    
    private function _assignUploadedByElementorFilesIndexes($fieldIndex)
    {
        $idx = 0;
        $this->_indexes = array();
        
        foreach ($_FILES['form_fields'] as $fIndex => $filesData) {
            foreach ($filesData as $fData) {
                if (empty($fData['tmp_name'])) continue;

                if ($fieldIndex === $fIndex) {
                    $this->_indexes[] =  $idx;
                }
                
                $idx++;
            }
        }
    }
}
