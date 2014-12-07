<?php
/**
 * Slim Caching Manager for the Slim Framework
 *
 * Use this class if you use Slim caching on resources which are being changed dynamically.
 * You can use ResourceHandler to store the cached data (Resource, Lifetime) wherever you want.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 * @version 1.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace SlimCachingManager\ResourceMapper;

use Slim\Slim;
use SlimCachingManager\ResourceHandler\IResourceHandler;

/**
 * Slim IResourceManager
 *
 * Interface for all ResourceMappers
 *
 * @package Slim\Http\Caching
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
interface IResourceMapper {

    public function setApplication(Slim $app);

    public function getApplication();

    public function setHandler(IResourceHandler $handler );

    public function getHandler();

    public function setHeaders();

}