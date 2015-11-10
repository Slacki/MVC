<?php

namespace Framework;

/**
 * Class ErrorHandler handles uncaught PHP errors and exceptions
 *
 * @package Framework
 */
class ErrorHandler
{
    /**
     * @var bool $discardExistingOutput determines if output before error display should be erased
     */
    public $discardExistingOutput = true;

    /**
     * @var \Exception beeing currently handled.
     */
    public $exception;

    /**
     * This method is responsible for registering all handlers.
     */
    public function register()
    {
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleFatalError']);
    }

    /**
     * Unregisters this error handler by resoring the PHP error and exception handlers.
     */
    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();
    }

    /**
     * Handles uncaught PHP exceptions
     *
     * @param $exception \Exception
     */
    public function handleException($exception)
    {
        $this->exception = $exception;
        // disable error capturing to avoid revursive errors
        // while handling exceptions
        $this->unregister();

        // preventive HTTP status code
        if (PHP_SAPI !== 'cli') {
            http_response_code(500);
        }

        try {
            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);
        } catch (\Exception $e) {
            // if error occurs while handling an exception
            $msg = "An Error occurred while handling another error:\n";
            $msg .= (string) $e;
            $msg .= "\nPrevious exception:\n";
            $msg .= (string) $exception;
            if (PHP_SAPI === 'cli') {
                echo $msg . "\n";
            } else {
                echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES, Yii::$app->charset) . '</pre>';
            }
            $msg .= "\n\$_SERVER = " . VarDumper::export($_SERVER);
            error_log($msg);
            exit(1);
        }

        $this->exception = null;
    }
    /**
     * Handles PHP runtime errors.
     *
     * @param $code
     * @param $message
     * @param $file
     * @param $line
     * @throws \ErrorException To be handled by handleException
     */
    public function handleError($code, $message, $file, $line)
    {
        if (error_reporting() & $code) {
            // manually load ErrorException
            if (!class_exists('Framework\\ErrorException', false)) {
                require_once(__DIR__ . '/ErrorException.php');
            }

            throw new ErrorException($message, $code, $code, $file, $line);
        }
    }
    /**
     * Handles fatal errors
     */
    public function handleFatalError()
    {
        // manually load ErrorException
        if (!class_exists('Framework\\ErrorException', false)) {
            require_once(__DIR__ . '/ErrorException.php');
        }

        $error = error_get_last();
        if (ErrorException::isFatalError($error)) {
            $exception = new ErrorException(
                $error['message'],
                $error['type'],
                $error['type'],
                $error['file'],
                $error['line']
            );
            $this->exception = $exception;

            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);

            exit(1);
        }
    }
    /**
     * Renders exception
     *
     * @param $exception \Exception
     */
    public function renderException($exception)
    {
        $view = new View();
        $view->viewsDirectory = __DIR__ . '/views/';
        $view->className = get_class($exception);
        $view->message   = $exception->getMessage();
        $view->file      = $exception->getFile();
        $view->line      = $exception->getLine();
        $view->fileBody  = $exception->getFile();
        $view->trace     = $exception->getTraceAsString();
        $view->render('error', false);
    }
    /**
     * Removes all output echoed before calling this method.
     */
    public function clearOutput()
    {
        // the following manual level counting is to deal with zlib.output_compression set to On
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
    }
}