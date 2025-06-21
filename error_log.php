<?php
class ErrorLogger {
    private $logFile;
    private $maxLogSize = 5242880; // 5MB

    public function __construct($logFile) {
        $this->logFile = $logFile;
        $this->rotateLogIfNeeded();
    }

    public function log($type, $message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';
        $logEntry = "[$timestamp] [$type] $message $contextStr\n";
        
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }

    private function rotateLogIfNeeded() {
        if (file_exists($this->logFile) && filesize($this->logFile) > $this->maxLogSize) {
            $backup = $this->logFile . '.' . date('Y-m-d-H-i-s') . '.bak';
            rename($this->logFile, $backup);
        }
    }

    public static function getInstance($logFile = 'logs/error.log') {
        static $instance = null;
        if ($instance === null) {
            if (!is_dir('logs')) {
                mkdir('logs', 0777, true);
            }
            $instance = new self($logFile);
        }
        return $instance;
    }
}

// Global error handler
function globalErrorHandler($errno, $errstr, $errfile, $errline) {
    $logger = ErrorLogger::getInstance();
    $logger->log('ERROR', $errstr, [
        'file' => $errfile,
        'line' => $errline,
        'type' => $errno
    ]);
    return false; // Let PHP's internal error handler continue
}

// Global exception handler
function globalExceptionHandler($exception) {
    $logger = ErrorLogger::getInstance();
    $logger->log('EXCEPTION', $exception->getMessage(), [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
}

// Set error handlers
set_error_handler('globalErrorHandler');
set_exception_handler('globalExceptionHandler');

// Log fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $logger = ErrorLogger::getInstance();
        $logger->log('FATAL', $error['message'], [
            'file' => $error['file'],
            'line' => $error['line']
        ]);
    }
});
?> 