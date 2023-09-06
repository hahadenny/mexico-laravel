<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\CompanyIntegrations;

class FfmpegService {
    public function convertH264(Request $request) {
        $result = array();
        if ($request->url && $request->media_id) {
            $filename = $request->media_id.'_h264_preview_'.basename($request->url);   
            $cmd = "ffmpeg -i $request->url -vcodec libx264 -acodec aac -pix_fmt yuv420p ".public_path()."/h264/$filename";
            exec($cmd);
            $result['filename'] = "/h264/$filename";
        }
        return $result;
    }
    
    public function removeH264(Request $request) {
        $result = array();
        if ($request->media_id) {
            $cmd = "rm ".public_path()."/h264/".$request->media_id."_*";
            exec($cmd);
        }
        return $result;
    }
}
