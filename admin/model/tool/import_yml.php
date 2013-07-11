<?php
class ModelToolImportYml extends Model {
	public function addManufacturer($data) {
      	$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer SET name = '" . $this->db->escape($data['name']) . "', sort_order = '" . (int)$data['sort_order'] . "'");
		
		$manufacturer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "manufacturer SET image = '" . $this->db->escape($data['image']) . "' WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");
		}
		
		foreach ($data['manufacturer_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_description SET manufacturer_id = '" . (int)$manufacturer_id . "', language_id = '" . (int)$language_id . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', seo_title = '" . $this->db->escape($value['seo_title']) . "', seo_h1 = '" . $this->db->escape($value['seo_h1']) . "'");
		}
		
		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "manufacturer_to_store SET manufacturer_id = '" . (int)$manufacturer_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
				
		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'manufacturer_id=" . (int)$manufacturer_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}
		
		$this->cache->delete('manufacturer');
		
		return $manufacturer_id;
	}
	
	public function addAttributeGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group SET sort_order = '" . (int)$data['sort_order'] . "'");
		
		$attribute_group_id = $this->db->getLastId();
		
		foreach ($data['attribute_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		return $attribute_group_id;
	}
	
	public function addAttribute($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "attribute SET attribute_group_id = '" . (int)$data['attribute_group_id'] . "', sort_order = '" . (int)$data['sort_order'] . "'");
		
		$attribute_id = $this->db->getLastId();
		
		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description SET attribute_id = '" . (int)$attribute_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
		}
		
		return $attribute_id;
	}
	
	public function addCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "category 
                            SET `parent_id`   = '" . (int)$data['parent_id'] . "',
								`top`         = '" . (int)$data['top'] . "',
                                `status` = 1,
                                `date_modified` = NOW(), 
                                `date_added` = NOW()
                        ");
		$categoryId = $this->db->getLastId();
		
        $this->db->query("INSERT INTO " . DB_PREFIX . "category_description 
                            SET `category_id` = '" . (int)$categoryId . "',
                                `language_id` = 1,
                                `name` = '". $this->db->escape(trim($data['name'])) ."'
                        ");

        $this->db->query("INSERT INTO " . DB_PREFIX . "category_to_store 
                            SET `category_id` = '" . (int)$categoryId . "',
                                `store_id`    = '0'
                        ");

		return $categoryId;
    }

	/*
    public function deleteCategories() {
        $this->db->query("TRUNCATE TABLE  `category`");
        $this->db->query("TRUNCATE TABLE  `category_description`");
        $this->db->query("TRUNCATE TABLE  `category_to_store`");
    }
	*/
	
    public function deleteProducts() {
        $this->db->query("TRUNCATE TABLE  `product`");
        $this->db->query("TRUNCATE TABLE  `product_attribute`");
        $this->db->query("TRUNCATE TABLE  `product_description`");
        $this->db->query("TRUNCATE TABLE  `product_discount`");
        $this->db->query("TRUNCATE TABLE  `product_image`");
        $this->db->query("TRUNCATE TABLE  `product_option`");
        $this->db->query("TRUNCATE TABLE  `product_option_value`");
        $this->db->query("TRUNCATE TABLE  `product_related`");
        $this->db->query("TRUNCATE TABLE  `product_reward`");
        $this->db->query("TRUNCATE TABLE  `product_special`");
        $this->db->query("TRUNCATE TABLE  `product_to_category`");
        $this->db->query("TRUNCATE TABLE  `product_to_download`");
        $this->db->query("TRUNCATE TABLE  `product_to_layout`");
        $this->db->query("TRUNCATE TABLE  `product_to_store`");
    }
	
	public function loadManufactures() {
		$manufactures = $this->db->query('SELECT * FROM `manufacturer`');
		
		$result = array();
		
		foreach ($manufactures->rows as $item) {
			$result[ $item['name'] ] = $item['manufacturer_id'];
		}
		
		return $result;
	}
	
	public function loadAttributes() {
		$attributes = $this->db->query('SELECT * FROM `attribute` INNER JOIN `attribute_description` ON `attribute_description`.attribute_id = `attribute`.attribute_id WHERE language_id = 1');
		
		$result = array();
		
		foreach ($attributes->rows as $item) {
			$result[ $item['name'] ] = $item['attribute_idyy'];
		}
		
		return $result;
	}
	
	/*
	public function deleteManufactures() {
		$this->db->query("TRUNCATE TABLE  `manufacturer`");
        $this->db->query("TRUNCATE TABLE  `manufacturer_description`");
        $this->db->query("TRUNCATE TABLE  `manufacturer_to_store`");
	}
	
	public function deleteAttributes() {
		$this->db->query("TRUNCATE TABLE  `attribute`");
		$this->db->query("TRUNCATE TABLE  `attribute_description`");
	}
	
	public function deleteAttributeGroups() {
		$this->db->query("TRUNCATE TABLE  `attribute_group`");
		$this->db->query("TRUNCATE TABLE  `attribute_group_description`");
	}
	*/
}
?>
