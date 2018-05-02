<?php
class ControllerExtensionModuleNoticeupUploadImage extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/noticeup_upload_image');
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['button_download'] = $this->language->get('button_download');
		$data['text_image_generate'] = $this->language->get('text_image_generate');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/noticeup_upload_image', 'token=' . $this->session->data['token'], true)
		);

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if(isset($this->session->data['log'])){
			$data['log'] = $this->session->data['log'];
			unset($this->session->data['log']);
		}else{
			$data['log'] = '';
		}

		$data['generate'] = $this->url->link('extension/module/noticeup_upload_image/generate', 'token=' . $this->session->data['token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/noticeup_upload_image', $data));
	}

	public function generate() {
		$this->load->language('extension/module/noticeup_upload_image');

		if (!$this->user->hasPermission('modify', 'extension/module/noticeup_upload_image')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			$this->db->query("TRUNCATE TABLE ".DB_PREFIX."product_image");
			$this->glob_recursive(DIR_IMAGE."catalog/products", ".jpg");
			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->response->redirect($this->url->link('extension/module/noticeup_upload_image', 'token=' . $this->session->data['token'], true));
	}

	private function glob_recursive($dir, $mask){
			$this->session->data['log'] = '';
			foreach(glob($dir.'/*') as $filename){
			$catalog = str_replace(DIR_IMAGE,'',$filename);
					if(strtolower(substr($filename, strlen($filename)-strlen($mask), strlen($mask)))==strtolower($mask)){
						$regexp_product_images = '/([0-9]+)\s.([0-9]+)./';
						if(preg_match($regexp_product_images, $filename) ? true : false){
							//Расширения файлов catalog/products/100 (2).jpg
							//Файлы записываются как дополнительные фото продукта
							$result = preg_match_all($regexp_product_images, $filename, $match);
							$this->db->query("INSERT INTO `oc_product_image` (`product_image_id`, `product_id`, `image`, `sort_order`) VALUES (NULL, '".(int) $match[1][0]."', '".$catalog."', '".(int) $match[2][0]."');");

							$this->session->data['log'] .= $match[1][0]. ' ' . $match[2][0] . ' '. $catalog.'
';
						}else{
							//Расширения файлов например catalog/products/100.jpg
							//Файлы записываются как основной фото продукта
							$result = preg_match_all('/([0-9]+)[\\'.$mask.']/', $filename, $match);
							$this->db->query("UPDATE `".DB_PREFIX."product` SET `image` = '$catalog' WHERE product_id = ".(int) $match[1][1]."");

							$this->session->data['log'] .= $match[1][1]. ' '. $catalog.'
';
						}
					}
					if(is_dir($filename)) glob_recursive($filename, $mask);
			}
	}
}
