<?php

namespace Kri55h\RedditSaver;

use Exception;

class RedditSaver
{
    private string $postURL;
    private array $postDATA;
    private string $videoURL;
    public function setPostURL(string $url): string
    {
        $urlInfo = parse_url($url);
        if(isset($urlInfo) && !empty($urlInfo) && isset($urlInfo['host']) && !empty($urlInfo['host']) && str_contains($urlInfo['host'],'reddit.com') && isset($urlInfo['path']) && isset($urlInfo['scheme'])){
            $this->postURL = $urlInfo['scheme']."://".$urlInfo['host'].$urlInfo['path'];
            if(str_ends_with($this->postURL,'/')){
                $this->postURL .= ".json";
            }else{
                $this->postURL .= "/.json";
            }

            $curl_handle=curl_init();
            curl_setopt($curl_handle, CURLOPT_URL,$this->postURL);
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
            $response = curl_exec($curl_handle);
            curl_close($curl_handle);

            if(curl_error($curl_handle)){
                throw new Exception(curl_error($curl_handle));
            }else{
                $this->postDATA = $data = json_decode($response,true);
                if(isset($data[0]['data']['children'][0]['data']['secure_media']['reddit_video']['is_gif']) && $data[0]['data']['children'][0]['data']['secure_media']['reddit_video']['is_gif']){
                    throw new Exception("Video is actually a gif");
                }else{
                    return $this->postURL;
                }
            }
        }else{
            throw new Exception("Invalid reddit video URL");
        }
    }

    public function getVideoURL(): bool
    {
        $data = $this->postDATA;
        if(isset($data[0]['data']['children'][0]['data']['secure_media']['reddit_video']['fallback_url']) && !empty($data[0]['data']['children'][0]['data']['secure_media']['reddit_video']['fallback_url'])){
            $this->videoURL = $data[0]['data']['children'][0]['data']['secure_media']['reddit_video']['fallback_url'];
            return true;
        }else{
            return false;
        }
    }

    public function saveVIDEO(string $fileName): bool
    {
        if(!isset($this->postURL)){
            throw new Exception('Failed to get video URL !');
        }else{
            $videoURL = $this->getVideoURL();
            if(!$videoURL){
                throw new Exception('Failed to get video URL !');
            }else{
                $videoContent = file_get_contents($this->videoURL);

                // Get the video's MIME type using PHP's FileInfo extension
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_buffer($fileInfo, $videoContent);
                finfo_close($fileInfo);

                // Set appropriate headers for download
                header('Content-Type: ' . $mimeType);
                header('Content-Disposition: attachment; filename="'.$fileName.'.mp4"');
                header('Content-Length: ' . strlen($videoContent));

                return $videoContent;
            }
        }
    }

}
