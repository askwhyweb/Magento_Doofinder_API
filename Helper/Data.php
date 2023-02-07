<?php
/**
 * Copyright © 2021 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace OrviSoft\MagentoDoofinder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
	protected $scopeConfig;
	protected $request;
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
	    $this->scopeConfig = $scopeConfig;
	    $this->request = $request;
        parent::__construct($context);
    }

	public function getConfig($key, $parent = 'doofinder/general/'){
		return $this->scopeConfig->getValue($parent . $key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

	public function getGeneralConfg($key){
		return $this->getConfig($key);
	}

	public function getSearchConfig($key){
		return $this->getConfig($key, 'doofinder/search_settings/');
	}

    public function isEnabled(): bool
    {
        return (bool) $this->getGeneralConfg('status');
    }

	public function prepareResults(){
		$resultsPerPage = $this->getSearchConfig('rpp');
		$page = 1;
		$query = '';
		$sort = '';
		$limit = (int)$this->getSearchConfig('min_length');
		//Price Attr Added in Search
		if($this->request->getParam('sort') && strlen($this->request->getParam('sort')) > 0){
			$sort = $this->request->getParam('sort');
		}
		if($this->request->getParam('q') && strlen($this->request->getParam('q')) >= $limit && strlen($this->request->getParam('q')) > 0){
			$query = $this->request->getParam('q');
		}
		if($this->request->getParam('product_list_limit') && strlen($this->request->getParam('product_list_limit')) > 0){
			$resultsPerPage = $this->request->getParam('product_list_limit');
		}
		if($this->request->getParam('p') && strlen($this->request->getParam('p')) > 0){
			$page = $this->request->getParam('p');
		}
		if($this->request->getParam('sort') && strlen($this->request->getParam('sort')) > 0){
			$data = array(
				'hashid' => $this->getGeneralConfg('hashid'),
				'rpp' => $resultsPerPage,
				'page' => $page,
				'query' => $query,
				'sort[][best_price]' => $sort,
			);
		} else {
			$data = array(
				'hashid' => $this->getGeneralConfg('hashid'),
				'rpp' => $resultsPerPage,
				'page' => $page,
				'query' => $query,
			);
		}
		$excludeList = ['q', 'p', 'SSID', 'product_list_limit', 'amp', 'sort'];
		$params = $this->request->getParams();
		foreach($params as $key => $value){
			if(in_array($key,$excludeList)){
				unset($params[$key]);
			}
		}
		foreach($params as $attribute => $searchValue){
			$data["filter[$attribute][]"] = $searchValue;
		}
		return $this->execute($data);
	}

	public function execute($data = []){
		if(!$this->isEnabled()){
			return false;
		}
		$zone = $this->getGeneralConfg('zone');
		$token = $this->getGeneralConfg('token');

		$url = "https://$zone-search.doofinder.com/5/search?".http_build_query($data);
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = array(
			'Accept: application/json',
			"Authorization: $token"
		);

		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);

		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$resp = curl_exec($curl);
		if(strlen($resp) > 0){
			$resp = json_decode($resp, true);
		}
		//echo '<h1>SentValues</h1><pre>'.print_r([$url, $headers, $data], true) . '</pre>';
		curl_close($curl);
		return $resp;
	}

	public function returnRequests($input = '', $return = false){
		if(strlen($input)){
			return $this->request->getParam($input, $return);
		}
		return $this->request->getParams();
	}
}

