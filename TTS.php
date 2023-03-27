<?php

namespace uzdevid\TTS;

use Exception;
use Yii;
use yii\base\Component;

/**
 * Class TTS
 * @package uzdevid\TTS
 * @property string $token
 * @property string $projectId
 * @property string $voice
 * @property bool $enableCache
 * @property int $cacheDuration
 */
class TTS extends Component {
    private string $_token;
    private string $_projectId;
    private string $_voice = TTSOptions::VOICE_MALE;
    public bool $enableCache = true;
    public int $cacheDuration = 3600 * 24 * 30;

    /**
     * @throws Exception
     */
    public function __construct(array $config = []) {
        parent::__construct($config);

        if (empty($this->_token)) {
            throw new Exception('Token is required');
        }

        if (empty($this->_projectId)) {
            throw new Exception('Project ID is required');
        }

        if (!in_array($this->_voice, [TTSOptions::VOICE_MALE, TTSOptions::VOICE_FEMALE])) {
            throw new Exception('Voice is not valid');
        }
    }

    public function getToken(): string {
        return $this->_token;
    }

    public function setToken(string $token): void {
        $this->_token = $token;
    }

    public function getProjectId(): string {
        return $this->_projectId;
    }

    public function setProjectId(string $projectId): void {
        $this->_projectId = $projectId;
    }

    public function getVoice(): string {
        return $this->_voice;
    }

    public function setVoice(string $voice): void {
        $this->_voice = $voice;
    }

    public function synthesize(string $text): string {
        $cacheKey = 'tts_' . md5($text);
        $cache = Yii::$app->cache;

        if ($this->enableCache && $cache->exists($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $raw = [
            'text' => $text,
            'voice' => $this->voice,
            'project_id' => $this->projectId,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cabinet.tts.uz/api/v1/common/synthesize/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($raw, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: token {$this->token}"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $cache->set($cacheKey, $response, $this->cacheDuration);
        return $response;
    }
}
