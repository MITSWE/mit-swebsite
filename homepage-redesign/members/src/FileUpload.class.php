<?php
/**
 * FileUpload - File upload helper for PHP 5 or higher
 *        
 * Copyright 1999 David Fox
 * Copyright 1999, 2005, iMarc <info@imarc.net>
 * 
 * @version  5.3.0
 *
 * @author   Dave Tufts [dt] <dave@imarc.net>
 * @author   David Fox [df]
 * @author   Fred LeBlanc [fl] <fred@imarc.net>
 * @author   William Bond [wb] <will@imarc.net>
 * 
 * @todo     checkMime(): incorporate PECL/fileinfo or PEAR/Mime_type; comare supplied 
 *           MIME type from browser, with what PHP returns; foor now, 'trust' the MIME 
 *           from user's browser. [dt, 2005-12-27]
 *           suggestExtension(): add more suggestions http://www.duke.edu/websrv/file-extensions.html 
 *           or read an external file that maps mime to extension [dt, 2005-12-27]
 * 
 * @changes  5.3.0  Added Brazilian Portuguese error messaging. [dt, 2006-08-21]
 * @changes  5.2.1  Fixed issue when there is a single file upload field with a name in the form "file[]" [wb, 2006-05-26]
 * @changes  5.2.0  Added resetError() method [wb, 2006-04-24]
 * @changes  5.1.2  Changed isUpload to look for empty filename and empty files (0 bytes filesize) [wb, 2006-04-06]
 * @changes  5.1.1  multiple bugfixes [dt, 2006-02-03]
 * @changes  5.1.0  Added public acceptPHP(), now the only way to allow php uploads [dt, 2006-01-19]
 * @changes  5.0.0  updated to comply with iMarc code standards [dt, 2006-01-10]
 * @changes  4.1.2  cleaned up comments [dt, 2006-01-05]
 * @changes  4.1.1  removed depreciated methods, cleaned up comments [dt, 2005-12-27]
 * @changes  4.1.0  set_overwrite_mode now also accepts "OVERWRITE", "RENAME", "NOTHING" and "ERROR" [fl, 2005-11-30]
 * @changes  4.0.1  bugfix to correctly resolve file['basename'] if renamed [dt]
 * @changes  4.0.0  Initial testing complete, Added get_version() [dt]
 * 
 * Language specific error messaging:
 *     + [fr] Frank from http://www.ibigin.com - French
 *     + [de] lmg from http://www.kishalmi.net - German
 *     + [nl] Andre, a.t.somers@student.utwente.nl - Dutch
 *     + [it] Enrico Valsecchi http://www.hostyle.it <admin@hostyle.it> - Italian
 *     + [fi] Dotcom Media Solutions, http://www.dotcom.ms - Finnish
 *     + [es] Alejandro Ramirez <alex@cinenganos.com> - Spanish
 *     + [no] Sigbjorn Eide <seide@tiscali.no> - Norwegian
 *     + [da] Thomas Hannibal http://hannibalsoftware.dk/ - Danish
 *     + [se] Johan Sandstrom <jsands@spray.se> - Swedish
 *     + [pt_br] Charles from http://turbosolutions.com.br - Brazilian Portuguese
 */
class FileUpload 
{

	/**
	 * Properties a single file
	 * 
	 * @var array
	 */
	private $version = "5.3.0";

	/**
	 * Properties of a single file
	 * 
	 * @var array
	 */
	private $file;
		
	/**
	 * Properties of all uploaded files (if one uploaded file, identical to $this->file)
	 * 
	 * @var array
	 */
	private $file_array;
	
	/**
	 * Error message, accessable via getError();
	 * 
	 * @var string
	 */
	private $error;

	/**
	 * Language of error message
	 * 
	 * @var string
	 */
	private $language;
	
	/**
	 * Upload directory path
	 * 
	 * @var string
	 */
	private $destination_dir;

	/**
	 * Mode to manage identically named files 
	 * 1 or OVERWRITE, 2 or RENAME, 3 or REJECT
	 * 
	 * @var int
	 */
	private $overwrite_mode;
	
	/**
	 * Case insensitive comma-speparated list of acceptable MIME types
	 * 
	 * @var string
	 */
	private $acceptable_mime_types = array();

	/**
	 * Comma-speparated list of unacceptable file extensions
	 * 
	 * @var array
	 */
	private $reject_extensions = array();

	/**
	 * Flags wether files ending in "php" can be uploaded
	 * 
	 * @var bool
	 */
	private $accept_php = false;

	/**
	 * Maximum byte size
	 * 
	 * @var int
	 */
	private $max_filesize;

	/**
	 * Max pixel width, if image
	 * 
	 * @var int
	 */
	private $max_image_width;
	
	/**
	 * Max pixel height, if image
	 * 
	 * @var int
	 */
	private $max_image_height;
	
	/**
	 * Default extension for upload(s)
	 * 
	 * @var string
	 */
	private $default_extension;
	
	
	/**
	 * Constructor; sets language preference
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  string language defaults to en (English).
	 * @return object
	 */
	public function __construct($language='en') 
	{
		$this->language = strtolower($language);
		$this->makeError(0);                      // clears errors
		$this->acceptPHP(FALSE);                  // by default, reject files ending with php
		$this->setDefaultExtension('');           // clear
		$this->setOverwriteMode('RENAME');        // default mode, rename new files
	}
	
	/**
	 * Returns version number
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  void
	 * @return string $version 
	 */
	public function getVersion() 
	{
		return $this->version;
	}
	
	/**
	 * Set mode to manage overwriting files
 	 *    + 1 or OVERWRITE: overwrites file with the same name
	 *    + 2 or RENAME:    if file exists, rename upload file
	 *    + 3 or REJECT:    if file exists, do nothing / flag error
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  mixed $mode  Must be 1,2, 3, OVERWRITE, RENAME, REJECT
	 * @return void
	 */
	public function setOverwriteMode($mode) 
	{
		$mode = (is_numeric($mode)) ? (int) $mode : strtoupper($mode);
		switch (TRUE) {
			case $mode == 1 || $mode == "OVERWRITE":
				$mode = 1;
				break;
			case $mode == 2 || $mode == "RENAME":
				$mode = 2;
				break;
			case $mode == 3 || $mode == "REJECT" || $mode == "NOTHING" || $mode == "ERROR":
				$mode = 3;
				break;
			default:
				$mode = 3;
		}
		$this->overwrite_mode = (int) $mode;
	}
	
	/**
	 * Sets flag to accept files ending in 'php'
	 * 
	 * @access public
	 * @since  5.1.0
	 * 
	 * @param  bool true allows users to upload files ending in 'php'
	 * @return void 
	 */
	public function acceptPHP($bool) 
	{
		$this->accept_php = (bool) $bool;
	}
	
	/**
	 * Set array of file endings to reject
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  mixed $reject  array or comma separated string of unacceptable file endings
	 * @return void
	 */
	public function setRejectExtensions($reject) 
	{
		if (!is_array($reject)) {
			$reject = explode(",", str_replace(' ', '', $reject));
		}
		$this->reject_extensions = array_unique($reject);
	}
	
	/**
	 * Returns array of unacceptable file endings
	 * 
	 * @access public
	 * @since  5.1.0
	 * 
	 * @param  void
	 * @return array
	 */
	protected function getRejectExtensions() 
	{
		return $this->reject_extensions;
	}
	
	/**
	 * Set array MIME types to accept; blank accepts all files
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  mixed $mime  array or comma separated string of acceptable MIME types
	 * @return void
	 */
	public function setAcceptableTypes($mime='') 
	{
		if (!is_array($mime)) {
			$mime = explode(",", str_replace(' ', '', $mime));
		}
		$this->acceptable_mime_types = array_unique($mime);
	}
	
	/**
	 * Set default filename extenstion; last resort if uploaded without 
	 * extension and PHP can't deduce extension based on MIME
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  string $default_extension
	 * @return void
	 */
	public function setDefaultExtension($default_extension) 
	{
		$this->default_extension = trim((string) $default_extension);
	}
	
	/**
	 * Set maximum upload filesize in bytes. PHP's configuration also 
	 * controls maximum upload size; usually defaults to 2Mb or 4Mb. 
	 * To upload larger files, change php.ini first.
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  int $size  filesize in bytes
	 * @return void
	 */
	public function setMaxFilesize($size) 
	{
		$this->max_filesize = (int) $size;
	}
	
	/**
	 * Set maximum pixel dimensions; ignored by non-image uploads
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  int $width maximum pixel width of image uploads
	 * @param  int $height maximum pixel height of image uploads
	 * @return void
	 */
	public function setMaxImageSize($width, $height) 
	{
		$this->max_image_width  = (int) $width;
		$this->max_image_height = (int) $height;
	}
		
	/**
	 * Initiate upload. After set_xxx() methods are called to set 
	 * preferences, this is the only public method called by user.
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  string $filename HTML form field name of uploaded file
	 * @param  string $destination upload directory
	 * @return mixed (bool) false if error; (string) filename if single file uploaded; (array) filenames if multiple uploads
	 */
	public function upload($filename='', $save_as='', $destination='') 
	{
		$this->makeError(0);
		if (!$this->isUpload($filename)) {
			return FALSE;
		}		
		$this->destination_dir = $this->makePath($destination);
		$this->file_array      = array();
		
		// $_FILES acts differently if HTML form field, type="file", is single upload (name='foo') or array (name='foo[]'):
		// single:  $_FILES = array( [$formfield] => array ([name]=>'..', [type]=>'..', [tmp_name]=>'..', ...) );
		// array: $_FILES = array( [$formfield] => array ([name]=>array([0]=>'..', [1]=>'..'), [type]=>array([0]=>'..', [1]=>'..'), ...));
		if (is_array($_FILES[$filename]['name'])) {
		
            $num_uploads = $this->countUploads($filename);
        	
			// MULTIPLE - loop through each, copy to internal var
			for ($i=0; $i<$num_uploads; $i++) {
				$this->file             = $this->newFileArray();
				$this->file['name']     = (isset($_FILES[$filename]['name'][$i]))     ? $_FILES[$filename]['name'][$i] : '';
				$this->file['type']     = (isset($_FILES[$filename]['type'][$i]))     ? $_FILES[$filename]['type'][$i] : '';
				$this->file['tmp_name'] = (isset($_FILES[$filename]['tmp_name'][$i])) ? $_FILES[$filename]['tmp_name'][$i] : '';
				$this->file['error']    = (isset($_FILES[$filename]['error'][$i]))    ? $_FILES[$filename]['error'][$i] : '';
				$this->file['size']     = (isset($_FILES[$filename]['size'][$i]))     ? $_FILES[$filename]['size'][$i] : '';
				
				if ($this->doUpload($this->file)) {
					$this->file_array[] = $this->file;
				}
			}
			if (!count($this->file_array)) { // no successful uploads
				if (!$this->error) { $this->makeError(1); }
				return FALSE;
			}
			
			// success; return array of filenames
			$return = array();
			foreach ($this->file_array as $key=>$file_array) {
				$return[] = $file_array['name'];
			}
			return $return;
		} else {
			
			// SINGLE - copy $_FILES array to internal var
			$this->file             = $this->newFileArray();			
			$this->file['name']     = (isset($_FILES[$filename]['name']))     ? $_FILES[$filename]['name'] : '';
			//get the file extension
			$ext = end(explode('.', $this->file['name']));
			if($save_as != "")
				$this->file['name'] = $save_as.".".$ext;
			
			$this->file['type']     = (isset($_FILES[$filename]['type']))     ? $_FILES[$filename]['type'] : '';
			$this->file['tmp_name'] = (isset($_FILES[$filename]['tmp_name'])) ? $_FILES[$filename]['tmp_name'] : '';
			$this->file['error']    = (isset($_FILES[$filename]['error']))    ? $_FILES[$filename]['error'] : '';
			$this->file['size']     = (isset($_FILES[$filename]['size']))     ? $_FILES[$filename]['size'] : '';
			
			if ($this->doUpload($this->file)) {
				$this->file_array = $this->file;
				return $this->file['name']; // success; return single filename
			} else {
				if (!$this->error) { $this->makeError(1); }
				return FALSE;
			}
		}
	}
	
	/**
	 * Returns file array of file(s)
	 *     [name] => final filename
	 *     [type] => MIME type
	 *     [tmp_name] => PHP's temp name
	 *     [error] => PHP's error code
	 *     [size] => filesize in bytes
	 *     [extension] => extension, including dot
	 *     [width] => pixel width (if image)
	 *     [height] => pixel height (if image)
	 *     [basename] => basename without extension
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  void
	 * @return array file attributes 
	 */
	public function getFile() 
	{
		return $this->file_array;
	}
	
	/**
	 * Returns error message
	 * 
	 * @access public
	 * @since  5.0.0
	 * 
	 * @param  void
	 * @return string error message
	 */
	public function getError() 
	{
		return $this->error;
	}
    
    /**
	 * Resets the error message
	 * 
	 * @access public
	 * @since  5.2.0
	 * 
	 * @param  void
	 * @return void
	 */
	public function resetError() 
	{
		$this->error = '';
	}
	
	/**
	 * Count uploads
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $filename
	 * @return int
	 */
	private function countUploads($filename) 
	{
		if (isset($_FILES[$filename]['name']) && is_array($_FILES[$filename]['name']) ) {
			return count($_FILES[$filename]['name']);
		} else {
			return 1;
		}
	}
	
	/**
	 * Validate upload
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $filename HTML form file field 
	 * @return bool TRUE, if HTML form is setup, and user uploaded a file
	 */
	private function isUpload($filename) 
	{
		if (!stristr($_SERVER['CONTENT_TYPE'], 'multipart/form-data')) {
			$this->makeError(8);
			return FALSE;
		}
		if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
			$this->makeError(8);
			return FALSE;
		}
		if (!isset($_FILES) || !isset($_FILES[$filename]) ) {
			$this->makeError(1);
			return FALSE;
		}
		
		if (!is_array($_FILES[$filename]) || empty($_FILES[$filename]['name'])  || empty($_FILES[$filename]['tmp_name']) || empty($_FILES[$filename]['size'])) {
			$this->makeError(1);
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Upload process manager; passes file through other methods
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  array $file_array reference to $this->file
	 * @return bool true, if file is uploaded and moved
	 */
	private function doUpload(&$file_array='') 
	{
		if (!isset($file_array) || !is_array($file_array)) {
			return FALSE;
		}
		if (isset($file_array['tmp_name']) && $file_array['tmp_name']) {
			
			$this->cleanFile($file_array);

			if (!$this->checkFile($file_array)) {
				unset($file_array['tmp_name']); // clean up temp file
				return FALSE;
			}
			
			if (!$this->moveFile()) {
				unset($file_array['tmp_name']); // clean up temp file
				return FALSE;
			}

			return TRUE;
		}
	}
		
	/**
	 * File clean up process manager
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  array $file_array reference to $this->file
	 * @return bool true, if file passes all checks
	 */
	private function cleanFile(&$file_array='') 
	{
		$file_array['name']   = $this->cleanFilename($file_array['name']);
		$file_array['width']  = $this->getImageWidth($file_array['tmp_name']);
		$file_array['height'] = $this->getImageHeight($file_array['tmp_name']);

		// find the best extension; PHP can figure out what images SHOULD use, or try MIME, 
		$extension = '';
		$supplied_extension = $this->getExtension($file_array['name']);
		$basename           = $this->getBaseFilename($file_array['name']);
		
		if(stristr($file_array['type'], "image")) {
			$image_extension = $this->getImageExtension($file_array['tmp_name']);

			if ($supplied_extension == $image_extension) {
				// filename's extension matched what PHP thought it should be
				$extension = $image_extension;
			} else {
				// mismatch: user uploaded 'foo.gif'; PHP thinks it's a jpg; call it 'foo.gif.jpg'
				$basename  = $basename . $supplied_extension;
				$extension = $image_extension;
			}
		} elseif ($supplied_extension) {
			$extension = $supplied_extension;
		} else {
			$extension = $this->suggestExtension($file_array['type']); // suggest ext, based on MIME
		}
		
		// last resort, use default_extenstion by setDefaultExtension()
		if (!$extension) {
			$extension = $this->default_extension;
		}
		
		// correct file array
		$file_array['extension'] = $extension;
		$file_array['basename']  = $basename;
		$file_array['name']      = $file_array['basename'] . $file_array['extension'];
		
		// text files, convert non-unix line breaks to unix
		$this->cleanLineBreaks($file_array['tmp_name'], $file_array['type']);
		
		return TRUE;
	}
	
	/**
	 * Suggest extension based on MIME type
	 *
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $mime MIME type
	 * @return string extension
	 */
	private function suggestExtension ($mime='') 
	{
		switch($mime) {
			case "text/plain":
				$extension = ".txt"; break;
			case "text/richtext":
				$extension = ".txt"; break;
			default:
				$extension = ""; break;
		}
		return $extension;
	}
	
	/**
	 * Get extension, including dot to a filename
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $name filename
	 * @return string extension
	 */
	private function getExtension ($filename='') 
	{
		if (stristr($filename, '.')) {
			$last_dot_position = strrpos($filename, ".");
		} else {
			$last_dot_position = strlen($filename);
		}
		return substr($filename, $last_dot_position, strlen($filename));
	}
	
	/**
	 * returns overwrite_mode
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  void
	 * @return int
	 */
	private function getOverwriteMode () 
	{
		return (int) $this->overwrite_mode;
	}
	
	/**
	 * Get part of filename before extension; if no extension, full filename is returned
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $name filename
	 * @return string extension
	 */
	private function getBaseFilename ($filename='') 
	{
		if (stristr($filename, '.')) {
			$last_dot_position = strrpos($filename, ".");
		} else {
			$last_dot_position = strlen($filename);
		}
		return substr($filename, 0, $last_dot_position );
	}
	
	/**
	 * Clean up filename; remove spaces, non-standar chars
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $name initial filename
	 * @return string filename
	 */
	private function cleanFilename($name='') 
	{
		$name = strtolower($name);
		$name = str_replace(" ", "_", str_replace("%20", "_", $name) );
		$name = ereg_replace("[^a-z0-9._]", "", $name);
		if ($name[0] == '.') { // first char is dot
			$name = "_".$name;
		}
		if (!strlen($name)) { // if all chars were stripped out by regex
			$name = 'uploaded_file';
		}
		return $name;
	}	
		
	/**
	 * Get image's pixel width
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $upload_file path and name to file
	 * @return mixed int number of pixels, if image; empty string, if not
	 */
	private function getImageWidth($upload_file) 
	{
		if ($image_properties = @getimagesize($upload_file)) {
			return $image_properties[0];
		}
		return '';
	}
	
	/**
	 * Get image's pixel height
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $upload_file path and name to file
	 * @return mixed int number of pixels, if image; empty string, if not
	 */
	private function getImageHeight($upload_file) 
	{
		if ($image_properties = @getimagesize($upload_file)) {
			return $image_properties[1];
		}
		return '';
	}
	
	/**
	 * Get image's type (from getimagesize())
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $upload_file path and name to file
	 * @return mixed int type from getimagesize(), if image; empty string, if not
	 */
	private function getImageType($upload_file) 
	{
		if ($image_properties = @getimagesize($upload_file)) {
			return $image_properties[2];
		}
		return '';
	}
	
	/**
	 * Get image's proper extension
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $upload_file path and name to file
	 * @return mixed string
	 */
	private function getImageExtension($upload_file) 
	{
		if ($type = $this->getImageType($upload_file)) {
			switch($type) {
				case 1:
					$ext = ".gif"; break;
				case 2:
					$ext = ".jpg"; break;
				case 3:
					$ext = ".png"; break;
				case 4:
					$ext = ".swf"; break;
				case 5:
					$ext = ".psd"; break;
				case 6:
					$ext = ".bmp"; break;
				case 7:
					$ext = ".tif"; break;
				case 8:
					$ext = ".tif"; break;
				default:
					$ext = ''; break;
			}
			return $ext;
		}
		return '';
	}
	
	/**
	 * Line breaks converted to Unix (non-text files ignored)
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $upload_file path and filename
	 * @param  string $type MIME type
	 * @return bool
	 */
	private function cleanLineBreaks($upload_file='', $type='') 
	{				
		if(stristr($type, "text")) {
			$new_file  = '';
			$old_file  = '';
			if ($fcontents = @file($upload_file)) {
				while (list ($line_num, $line) = each($fcontents)) {
					$old_file .= $line;
					if (stristr($line, chr(13).chr(10))) {
						$new_file .= str_replace(chr(13).chr(10), chr(10), $line); // convert windows breaks (CRLF) to unix
					} else {
						$new_file .= str_replace(chr(13), chr(10), $line); // convert mac breaks (CR) to unix
					}
				}
			}
			if ($old_file != $new_file) { // Open uploaded file; re-write with the new breaks
				if ($fp = fopen($upload_file, "w")) {
					fwrite($fp, $new_file);
					fclose($fp);
				}
			}
		}
	}
	
	/**
	 * Acceptance mangager; perorms checks to see if the file is acceptable
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  array $file_array reference to $this->file
	 * @return bool
	 */
	private function checkFile(&$file_array='') 
	{				
		// validate filesize
		if (!$this->checkFilesize($file_array['size'])) {
			return FALSE;
		}
		// validate pixel dimensions
		if (!$this->checkImageSize($file_array['width'], $file_array['height'], $file_array['type'])) {
			return FALSE;
		}
		// validate MIME
		if (!$this->checkMime($file_array['type'])) {
			return FALSE;
		}
		// validate extension
		if (!$this->checkExtension($file_array['name'])) {
			return FALSE;
		}
		return TRUE; // passed all checks
	}
	
	/**
	 * Checks upload filesize against class var max_filesize
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  int $size upload filesize in byes
	 * @return bool
	 */
	private function checkFilesize($size='') 
	{
		if($this->max_filesize && ($size > $this->max_filesize)) {
			$this->makeError(2);
			return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Checks pixel dimension against class vars max_image_width/height
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  int $width number of pixels in upload width
	 * @param  int $height number of pixels in upload height
	 * @param  string $type MIME type of upload; only checks if 'image' in MIME
	 * @return bool
	 */
	private function checkImageSize($width='', $height='', $type='') 
	{
		if (stristr($type, "image")) {
			if ($this->max_image_width || $this->max_image_height) {
				if  ( ((int)  $width > $this->max_image_width) || ((int) $height > $this->max_image_height) ) {
					$this->makeError(3);
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	
	/**
	 * Checks MIME type against class var for acceptable MIMEs
	 *
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $mime MIME type of uploaded file
	 * @return bool
	 */
	private function checkMime($mime='') 
	{
		if (!empty($this->acceptable_mime_types)) {
			if (!trim($mime['type'])) { // browser didn't send mime type; reject file
				$this->makeError(4);
				return FALSE;
			}
			$accept = FALSE; // set to true is a MIME type matches
			foreach ($this->acceptable_mime_types as $acceptable_mime) {
				if (preg_match("|".preg_quote($acceptable_mime)."|i", $mime)) {
					$accept = TRUE;
				}
			}
			if (!$accept) {
				$this->makeError(4);
				return FALSE;
			}
		}
		return TRUE;
	}	
	
	/**
	 * Checks upload filename type against class var for acceptable extension(s)
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $filename filename of uploaded file
	 * @return bool
	 */
	private function checkExtension($filename='') 
	{
		$this->setRejectStatusForPHP();
		foreach ($this->getRejectExtensions() as $bad_extension) {
			if (preg_match("|".preg_quote($bad_extension)."$|i", $filename)) {
				$this->makeError(7);
				return FALSE;
			}
		}
		return TRUE;
	}
	
	/**
	 * Calls setRejectExtenstions based on $this->acceptPHP()
	 * 
	 * @access private
	 * @since  5.1.0
	 * 
	 * @param  void
	 * @return void calls setRejectExtensions
	 */
	private function setRejectStatusForPHP()
	{
		$rejects = $this->getRejectExtensions();
		if ($this->accept_php) {
			unset($rejects['php']);
		} else {
			array_push($rejects, 'php');
		}
		$this->setRejectExtensions($rejects);
	}
	
	/**
	 * Moves $this->file to $this->destination_dir, renames file if neccisary
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  void
	 * @return bool
	 */
	private function moveFile() 
	{
		// error set somewhere else, exit
		if ($this->error) return FALSE;
		
		switch($this->getOverwriteMode()) {
			case 1: // overwrite file with same name
				if (!@move_uploaded_file($this->file['tmp_name'], $this->destination_dir . "/" . $this->file['name'])) {
					$this->makeError(6);
					return FALSE;
				}
				break;
			case 2: // if file exists, rename upload file
				$copy = "";	
				$n    = 1;
				
				while(file_exists($this->destination_dir . "/" . $this->file['basename'] . $copy . $this->file['extension'])) {
					$copy = "_copy" . $n;
					++$n;
				}
				$this->file['basename'] = $this->file['basename'] . $copy;
				$this->file['name']     = $this->file['basename'] . $this->file['extension'];
				if (!@move_uploaded_file($this->file['tmp_name'], $this->destination_dir . "/" . $this->file['name'])) {
					$this->makeError(6);
					return FALSE;
				}
				break;
			default: // if filename exists, do nothing / flag error
				if (file_exists($this->destination_dir . "/" . $this->file['name'])) {
					$this->makeError(5);
					return FALSE;
				} else {
					if (!@move_uploaded_file($this->file['tmp_name'], $this->destination_dir . "/" . $this->file['name'])) {
						$this->makeError(6);
						return FALSE;
					}
				}
				break;
		}
		return TRUE;
	}
	
	/**
	 * Returns nicely formated directory path
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  string $path
	 * @return string path, without trailing slash
	 */
	private function makePath($path='') 
	{
		$path = preg_replace("/\/$/", "", $path); // remove trailing slash
		if (strlen($path) == 0) {
			$path = ".";
		}
		if (!preg_match("/(^\.|\/)/", $path)) {
			$path = "./" . $path;
		}
		return $path;
	}
	
	/**
	 * Creates emply file array for single upload
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  void
	 * @return array empty file array
	 */
	private function newFileArray() 
	{
		$a = array();
		$a['name']      = ""; // file name
		$a['type']      = ""; // MIME type
		$a['tmp_name']  = ""; // upload/tmp name; from PHP's $_FILES array
		$a['error']     = ""; // PHP error code; from PHP's $_FILES array
		$a['size']      = ""; // filesize in bytes
		$a['extension'] = ""; // file extension including dot ".xxx"
		$a['width']     = ""; // if image, pixel width
		$a['height']    = ""; // if image, pixel height
		$a['basename']  = ""; // file name preceding extension
		return $a;
	}
	
	/**
	 * Returns correct error message based on language and code
	 * 
	 * @access private
	 * @since  5.0.0
	 * 
	 * @param  int $error_code
	 * @return string error message
	 */
	private function makeError($error_code='') 
	{
		$error_code = (int) $error_code;
		
		$error_message    = array();
		$error_message[0] = ''; // no error
		
		switch ( $this->language ) {
			// French (fr)
			case 'fr':
				$error_message[1] = "Aucun fichier n'a &eacute;t&eacute; envoy&eacute;";
				$error_message[2] = "Taille maximale autoris&eacute;e d&eacute;pass&eacute;e. Le fichier ne doit pas &ecirc;tre plus gros que " . $this->max_filesize/1000 . " Ko (" . $this->max_filesize . " octets).";
				$error_message[3] = "Taille de l'image incorrecte. L'image ne doit pas d&eacute;passer " . $this->max_image_width . " pixels de large sur " . $this->max_image_height . " de haut.";
				$error_message[4] = "Type de fichier incorrect. Seulement les fichiers de type " . implode(" or ", $this->acceptable_mime_types) . " sont autoris&eacute;s.";
				$error_message[5] = "Fichier '" . $this->destination_dir . "/" . $this->file['name'] . "' d&eacute;j&aacute; existant, &eacute;crasement interdit.";
				$error_message[6] = "La permission a ni&eacute;. Incapable pour copier le fichier &aacute; '" . $this->destination_dir . "/" . "'";
				$error_message[7] = "Les fichiers dont le nom se termine par " . implode(" or ", $this->getRejectExtensions()) . " ne peuvent pas &ecirc;tre envoy&eacute;s";
				$error_message[8] = "Erreur d'organisation. La forme doit contenir: method=\"POST\" enctype=\"multipart/form-data\"";
			break;
			
			// German (de)
			case 'de':
				$error_message[1] = "Es wurde keine Datei hochgeladen";
				$error_message[2] = "Maximale Dateigr&ouml;sse &uuml;berschritten. Datei darf nicht gr&ouml;sser als " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " bytes) sein.";
				$error_message[3] = "Maximale Bildgr&ouml;sse &uuml;berschritten. Bild darf nicht gr&ouml;sser als " . $this->max_image_width . " x " . $this->max_image_height . " pixel sein.";
				$error_message[4] = "Nur " . implode(" oder ", $this->acceptable_mime_types) . " Dateien d&uuml;rfen hochgeladen werden.";
				$error_message[5] = "Datei '" . $this->destination_dir . "/" . $this->file['name'] . "' existiert bereits.";
				$error_message[6] = "Erlaubnis hat verweigert. Unf&amul;hig, Akte zu '" . $this->destination_dir . "/" . "'";
				$error_message[7] = "Filenames ending with " . implode(" oder ", $this->getRejectExtensions()) . " may not be uploaded";
				$error_message[8] = "Aufstellungsfehler. Form muss enthalten: method=\"POST\" enctype=\"multipart/form-data\"";
			break;
			
			// Dutch (nl)
			case 'nl':
				$error_message[1] = "Er is geen bestand geupload";
				$error_message[2] = "Maximum bestandslimiet overschreden. Bestanden mogen niet groter zijn dan " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " bytes).";
				$error_message[3] = "Maximum plaatje omvang overschreven. Plaatjes mogen niet groter zijn dan " . $this->max_image_width . " x " . $this->max_image_height . " pixels.";
				$error_message[4] = "Alleen " . implode(" of ", $this->acceptable_mime_types) . " bestanden mogen worden geupload.";
				$error_message[5] = "Bestand '" . $this->destination_dir . "/" . $this->file['name'] . "' bestaat al.";
				$error_message[6] = "Toestemming is geweigerd. Kon het bestand niet naar '" . $this->destination_dir . "/" . "' copieren.";
				$error_message[7] = "Filenames ending with " . implode(" of ", $this->getRejectExtensions()) . " may not be uploaded";
				$error_message[8] = "De opstelling fout. Het formulier moet bevatten: method=\"POST\" enctype=\"multipart/form-data\"";
			break;
			
			// Italian (it)
			case 'it':
				$error_message[1] = "Il file non e' stato salvato";
				$error_message[2] = "Il file e' troppo grande. La dimensione massima del file e' " . $this->max_filesize/1000 . " Kb (" . $this->max_filesize . " bytes).";
				$error_message[3] = "L'immagine e' troppo grande. Le dimensioni massime non possono essere superiori a " . $this->max_image_width . " pixel di larghezza per " . $this->max_image_height . " d'altezza.";
				$error_message[4] = "Il tipo di file non e' valido. Solo file di tipo " . implode(" o ", $this->acceptable_mime_types) . " sono autorizzati.";
				$error_message[5] = "E' gia' presente un file con nome " . $this->destination_dir . "/" . $this->file['name'];
				$error_message[6] = "Permesso negato. Impossibile copiare il file in '" . $this->destination_dir . "/" . "'";
				$error_message[7] = "Filenames ending with " . implode(" o ", $this->getRejectExtensions()) . " may not be uploaded";
				$error_message[8] = "Installare l'errore. La forma deve contenere: method=\"POST\" enctype=\"multipart/form-data\"";
			break;
			
  			// Finnish
			case 'fi':
				$error_message[1] = "Tiedostoa ei l&amul;hetetty.";
				$error_message[2] = "Tiedosto on liian suuri. Tiedoston koko ei saa olla yli " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " tavua).";
				$error_message[3] = "Kuva on liian iso. Kuva ei saa olla yli " . $this->max_image_width . " x " . $this->max_image_height . " pikseli&amul;.";
				$error_message[4] = "Vain " . implode(" tai ", $this->acceptable_mime_types) . " tiedostoja voi tallentaa kuvapankkiin.";
				$error_message[5] = "Tiedosto '" . $this->destination_dir . "/" . $this->file['name'] . "' on jo olemassa.";
				$error_message[6] = "Ei k&amul;ytt&ouml;oikeutta. Tiedostoa ei voi kopioida hakemistoon '" . $this->destination_dir . "/" . "'";
				$error_message[7] = "Tiedostoja, joiden p&auml;&auml;te on " . implode(" tai ", $this->getRejectExtensions()) . " ei voida ladata.";
				$error_message[8] = "Asetus erehdys. Asu raivo hillit&auml;: method=\"POST\" enctype=\"multipart/form-data\"";
			break;
			
 			// Spanish
			case 'es':
				$error_message[1] = "No se subi&oacute; ning&uacute;n archivo.";
				$error_message[2] = "Se excedi&oacute; el tama&ntilde;o m&aacute;ximo del archivo. El archivo no puede ser mayor a " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " bytes).";
				$error_message[3] = "Se excedieron las dimensiones de la imagen. La imagen no puede medir m&aacute;s de " . $this->max_image_width . " (w) x " . $this->max_image_height . " (h) pixeles.";
				$error_message[4] = "El tipo de archivo no es v&aacute;lido. S&oacute;lo los archivos " . implode(" o ", $this->acceptable_mime_types) . " son permitidos.";
				$error_message[5] = "El archivo '" . $this->destination_dir . "/" . $this->file['name'] . "' ya existe.";
				$error_message[6] = "Permiso denegado. No es posible copiar el archivo a '" . $this->destination_dir . "/" . "'";
				$error_message[7] = "Los archivos que terminan con " . implode(" o ", $this->getRejectExtensions()) . " no pueden ser subidos.";
				$error_message[8] = "Error de arreglo. La forma debe contener: method=\"POST\" enctype=\"multipart/form-data\"";
			break;		
			
			// Norwegian
			case 'no':
				$error_message[1] = "Ingen fil ble lastet opp.";
				$error_message[2] = "Max filst&oslash;rrelse ble oversteget. Filen kan ikke vre st&oslash;rre ennn " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " byte).";
				$error_message[3] = "Max bildest&oslash;rrelse ble oversteget. Bildet kan ikke vre st&oslash;rre enn " . $this->max_image_width . " x " . $this->max_image_height . " piksler.";
				$error_message[4] = "Bare " . implode(" tai ", $this->acceptable_mime_types) . " kan lastes opp.";
				$error_message[5] = "Filen '" . $this->destination_dir . "/" . $this->file['name'] . "' finnes fra f&oslash;r.";
				$error_message[6] = "Tilgang nektet. Kan ikke kopiere filen til '" . $this->destination_dir . "/" . "'";
				$error_message[7] = "Filenames ending with " . implode(" tai ", $this->getRejectExtensions()) . " may not be uploaded";
				$error_message[8] = "Arrangementfeil. Form inneholder: method=\"POST\" enctype=\"multipart/form-data\"";
			break;
			
			// Danish
			case 'da':
				$error_message[1] = "Ingen fil blev uploaded";
				$error_message[2] = "Den maksimale filstrrelse er overskredet. Filerne m ikke vre strre end " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " bytes).";
				$error_message[3] = "Den maksimale billedstrrelse er overskredet. Billeder m ikke vre strre end " . $this->max_image_width . " x " . $this->max_image_height . " pixels.";
				$error_message[4] = "Kun " . implode(" or ", $this->acceptable_mime_types) . " kan uploades.";
				$error_message[5] = "Filen '" . $this->destination_dir . "/" . $this->file['name'] . "' eksisterer allerede.";
				$error_message[6] = "Adgang ngtet! Er ikke i stand til at kopiere filen til '" . $this->destination_dir . "/" . "'";
				$error_message[7] = "Filenames ending with " . implode(" or ", $this->getRejectExtensions()) . " may not be uploaded";
				$error_message[8] = "Setup fejl. Skema skal indeholde: method=\"POST\" enctype=\"multipart/form-data\"";
			break;
			
			// Swedish
			case 'se':
				$error_message[1] = "Ingen fil laddades upp";
				$error_message[2] = "Den maximala filstorleken &ouml;verskreds. Filer f&aring;r inte vara st&ouml;rre &auml;n " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " bytes).";
				$error_message[3] = "Den maximala bildstorleken &ouml;verskreds. Bilder f&aring;r inte vara st&ouml;rre &auml;n " . $this->max_image_width . " x " . $this->max_image_height . " pixels.";
				$error_message[4] = "Endast " . implode(" or ", $this->acceptable_mime_types) . " f&aring;r laddas upp.";
				$error_message[5] = "Filen '" . $this->destination_dir . "/" . $this->file["name"] . "' finns redan.";
				$error_message[6] = "Tillg&aring;ng nekad! Kan inte kopiera filen till '" . $this->destination_dir . "/" . "'";
				$error_message[7] = "Filenames ending with " . implode(" or ", $this->getRejectExtensions()) . " may not be uploaded";
				$error_message[8] = "Setup misstag. Form m&aring;ste inneh&aring;lla: method=\"POST\" enctype=\"multipart/form-data\"";
			break;

			// Brazilian Portuguese 
			case 'pt_br':
				$error_message[1] = "Nenhum arquivo foi enviado.";
				$error_message[2] = "Excedido o tamanho m&aacute;ximo do arquivo. O arquivo n&atilde;o pode ser maior que " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " bytes).";
				$error_message[3] = "Excedido o tamanho m&aacute;ximo da imagem. O tamanho da imagem n&atilde;o deve ultrapassar " . $this->max_image_width . " x " . $this->max_image_height . " pixels.";
				$error_message[4] = "Somente arquivos do tipo " . str_replace(",", " ou ", $this->acceptable_mime_types) . " podem ser enviados.";
				$error_message[5] = "O arquivo '" . $this->destination_dir . $this->file['name'] . "' j&aacute; existe.";
				$error_message[6] = "Permiss&atilde; negado. N&atilde;o foi poss&iacute;vel copiar o arquivo  '" . $this->destination_dir . "'";
				$error_message[7] = "Arquivos com as extens&otilde;oes " . str_replace(",", " ou ", $this->reject_extensions) . "n‹o podem ser enviados";
				$error_message[8] = "Erro: O elemento Form deve conter: method=\"POST\" enctype=\"multipart/form-data\"";
			break;


			// English
			default:
				$error_message[1] = "No file was uploaded";
				$error_message[2] = "Maximum file size exceeded. File may be no larger than " . $this->max_filesize/1000 . " KB (" . $this->max_filesize . " bytes).";
				$error_message[3] = "Maximum image size exceeded. Image may be no more than " . $this->max_image_width . " x " . $this->max_image_height . " pixels.";
				$error_message[4] = "Only " . implode(" or ", $this->acceptable_mime_types) . " files may be uploaded.";
				$error_message[5] = "File '" . $this->destination_dir . "/" . $this->file['name'] . "' already exists.";
				$error_message[6] = "Permission denied. Unable to copy file to '" . $this->destination_dir . "/" . "'";
				$error_message[7] = "Filenames ending with " . implode(" or ", $this->getRejectExtensions()) . " may not be uploaded";
				$error_message[8] = "Setup error. Form must contain: method=\"POST\" enctype=\"multipart/form-data\"";
			break;
		}
		
		$this->error = $error_message[$error_code];
		return $this->error;
	}
}

// +-----------------------------------------------------------------------+
// | Copyright (c) 1999, David Fox, Angryrobot Productions;                |
// | Copyright (c) 2000-2005, Dave Tufts, iMarc LLC                        |
// | All rights reserved.                                                  |
// |                                                                       |
// | Redistribution and use in source and binary forms, with or without    |
// | modification, are permitted provided that the following conditions    |
// | are met:                                                              |
// |                                                                       |
// | 1. Redistributions of source code must retain the above copyright     |
// |    notice, this list of conditions and the following disclaimer.      |
// | 2. Redistributions in binary form must reproduce the above            |
// |    copyright notice, this list of conditions and the following        |
// |    disclaimer in the documentation and/or other materials provided    |
// |    with the distribution.                                             |
// | 3. Neither the name of author nor the names of its contributors       |
// |    may be used to endorse or promote products derived from this       |
// |   software without specific prior written permission.                 |
// |                                                                       |
// | THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS "AS IS"      |
// | AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED     |
// | TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A       |
// | PARTICULAR PURPOSE ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR   |
// | CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,          |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF      |
// | USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED       |
// | AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT           |
// | LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING        |
// | IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF        |
// | THE POSSIBILITY OF SUCH DAMAGE.                                       |
// |                                                                       |
// +-----------------------------------------------------------------------+
?>
