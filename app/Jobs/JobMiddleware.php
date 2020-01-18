<?php

namespace App\Jobs;

use App\Models\System\JobExceptionModel;
use Closure;

class JobMiddleware
{

    public function handle($job, Closure $next)
    {
        $start = time();
        $handle = false;

        try {
            $handle = $next($job);
            $this->getValues($job, null, $start);
        } catch (\Exception $e) {
            $data = $this->getValues($job, $e, $start);
            JobExceptionModel::saveException($data);
        }
        return $handle;
    }

    private function getValues($job, $e, $start)
    {
        $data = [
            'job' => get_class($job),
            'execution_time' => time() - $start,
            'status' => $e ? JobExceptionModel::ABNORMAL_STATUS : JobExceptionModel::NORMAL_STATUS
        ];

        if ($e instanceof \Exception) {
            $tmp = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'exception' => $e
            ];
        } else {
            $tmp = [
                'exception' => ''
            ];
        }
        return array_merge($data, $tmp);
    }
}
