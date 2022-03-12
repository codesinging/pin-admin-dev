<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

namespace CodeSinging\PinAdmin\Controllers;

use CodeSinging\PinAdmin\Support\Routing\BaseController;
use Illuminate\View\Factory;
use Illuminate\View\View;

class AuthController extends BaseController
{
    public function index(): Factory|View
    {
        return admin_page('auth.index');
    }
}
