<?php
class ModelExtensionModuleOcTranslate extends Model {
    public function translate($text, $source, $target) {
        $api_key = $this->config->get('module_oc_translate_api_key');
        
        $url = 'https://translation.googleapis.com/language/translate/v2';
        
        $fields = array(
            'q' => $text,
            'source' => $source,
            'target' => $target,
            'format' => 'text',
            'key' => $api_key
        );
        
        $fields_string = http_build_query($fields);
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        $response = json_decode($result, true);
        
        if (isset($response['data']['translations'][0]['translatedText'])) {
            return $response['data']['translations'][0]['translatedText'];
        }
        
        return false;
    }
}
