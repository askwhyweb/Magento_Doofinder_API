<?php
/**
 * Copyright © 2021 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace OrviSoft\MagentoDoofinder\Block\Catalog\Search;

class Results extends \Magento\Framework\View\Element\Template
{
	protected $helper;
	protected $eavConfig;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = [],
        \OrviSoft\MagentoDoofinder\Helper\Data $helper,
        \Magento\Eav\Model\Config $eavConfig
    ) {
	    $this->helper = $helper;
	    $this->eavConfig = $eavConfig;
        parent::__construct($context, $data);
    }
	
	public function getResults(){
		if(!$this->helper->isEnabled()){
			echo "Module is disabled.";
			return;
		}
		return $this->helper->prepareResults();
	}
	
	public function getAttributeValue($attribute_code, $option_id)
	{
		$attribute = $this->eavConfig->getAttribute('catalog_product', $attribute_code);
		if($attribute){
			return $attribute->getSource()->getOptionText($option_id);
		}
		return false;
	}
	
	public function getAttributeData($attribute_code, $option_id, $addLi = true){
		//$testID = (int) $option_id;
		$testID = 0;
		if($testID > 1){
			$value = $this->getAttributeValue($attribute_code, $testID);
		}else{
			$value = $option_id;
		}
		if($value && !strlen($value)){
			return '';
		}else{
			return ($addLi ? '<li>' : '') . $value . ($addLi ? '</li>' : '');
		}
	}
	
	public function renderOptions($attribute_codes, $product){
		$output = '';
		
		if(!is_array($attribute_codes)){
			return;
		}
		foreach($attribute_codes as $_attribute_code => $appendValue){
			if(!isset($product[$_attribute_code])){
				continue;
			}
			if($_attribute_code == 'screen_size' && isset($product['maximum_resolution'])) {
				$output .= (strlen($this->getAttributeData($_attribute_code, $product[$_attribute_code], false)) > 0 || $this->getAttributeData('maximum_resolution', $product['maximum_resolution'], false) > 0) ?'<li>'.$this->getAttributeData($_attribute_code, $product[$_attribute_code], false) . "$appendValue" . ", " . $this->getAttributeData('maximum_resolution', $product['maximum_resolution'], false) . '</li>' : '';
			}elseif($_attribute_code == 'display_technology'){
				$output .= strlen($this->getAttributeData($_attribute_code, $product[$_attribute_code], false)) > 0?  "<li>$appendValue" . $this->getAttributeData($_attribute_code, $product[$_attribute_code], false) . '</li>' : '' ;
			}else{ 
				$output .= strlen($this->getAttributeData($_attribute_code, $product[$_attribute_code], false)) ? '<li>'.$this->getAttributeData($_attribute_code, $product[$_attribute_code], false) . "$appendValue" . '</li>' : '';
			} 
		}
		return $output;
	}
}

