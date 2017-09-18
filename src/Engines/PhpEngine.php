<?php

namespace Itxiao6\View\Engines;

use Couchbase\Exception;
use Itxiao6\Database\QueryException;
use MongoDB\Driver\Exception\ExecutionTimeoutException;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;

class PhpEngine implements EngineInterface
{
    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array   $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        return $this->evaluatePath($path, $data);
    }

    /**
     * Get the evaluated contents of the view at the given path.
     *
     * @param  string  $__path
     * @param  array   $__data
     * @return string
     */
    protected function evaluatePath($__path, $__data)
    {
        $obLevel = ob_get_level();

        ob_start();

        extract($__data);

        // We'll evaluate the contents of the view inside a try/catch block so we can
        // flush out any stray output that might get out before an error occurs or
        // an exception is thrown. This prevents any partial views from leaking.
        try {
            include $__path;
        }
        catch (\MongoExecutionTimeoutException $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }
        catch (ExecutionTimeoutException $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }
        catch (ExtraAttributesException $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }
        catch (Exception $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }
        catch (\PDOException $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }
        catch (QueryException $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }
        catch (\SQLiteException $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }
        catch (\ErrorException $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }
        catch (\Exception $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }
        catch (\ParseError $exception){
            \Service\Exception::__throw($exception -> getMessage(),$exception ->getCode(),$exception -> getPrevious(),$exception -> getFile(),$exception -> getLine());
        }

        return ltrim(ob_get_clean());
    }

    /**
     * Handle a view exception.
     *
     * @param  \Exception  $e
     * @param  int  $obLevel
     * @return void
     *
     * @throws $e
     */
    protected function handleViewException($e, $obLevel)
    {
        while (ob_get_level() > $obLevel) {
            ob_end_clean();
        }

        throw $e;
    }
}
