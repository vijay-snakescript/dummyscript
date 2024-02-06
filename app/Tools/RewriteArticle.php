<?php

namespace App\Tools;

use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class RewriteArticle implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.rewrite-article', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => "required|min:1|max_words:{$tool->wc_tool}",
        ]);

        $article = $request->input('string');

        // parse with driver
        $driver = (new ToolsManager($tool))->driver();
        $result = $driver->parse($article);

        if (!$result['success']) {
            return redirect()->back()->withError($result['message']);
        }

        $results = [
            'original_article' => $article,
            'article_rewrite' => $result['text']
        ];

        if ($tool->is_home == 1 && empty($request->route()->parameters)) {
            $relevant_tools = Tool::with('translations')->withCount('usageToday')->with('category')->active()->take('16')->get();
            $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool, $results), $tool->index_content);

            $plans = Plan::active()
                ->with('properties')
                ->with('translations')
                ->get();

            $faqs = Faqs::active()->get();
            $properties = Property::active()->with('translations')->get();

            return view('tools.pages.rewrite-article', compact('results', 'tool', 'plans', 'faqs', 'properties', 'relevant_tools'));
        }

        return view('tools.rewrite-article', compact('results', 'tool'));
    }

    public static function getFileds()
    {
        $array = [
            'title' => "Drivers",
            'fields' => [
                [
                    'id' => "driver",
                    'field' => "tool-options-select",
                    'placeholder' => "Driver",
                    'label' => "Driver",
                    'required' => true,
                    'options' => [['text' => "Default", 'value' => "DefaultRewriter"], ['text' => "Open  AI", 'value' => "OpenAiRewriter"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "openai_apikey",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter api key here....",
                    'label' => "OpenAi Driver Api Key",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,OpenAiRewriter",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "OpenAiRewriter"],
                ],
            ],
            "default" => ['driver' => 'DefaultRewriter']
        ];

        return $array;
    }

    public function index(Tool $tool, $relevant_tools, $plans, $faqs, $properties)
    {
        $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool), $tool->index_content);

        return view('tools.pages.rewrite-article', compact('tool', 'relevant_tools', 'plans', 'properties', 'faqs'));
    }

    public function toolForm($tool, $results = null)
    {
        return view('tools.pages.forms.rewrite-article', compact('tool', 'results'))->render();
    }

    public function indexContent()
    {
        $data = '<div class="raw-html-embed"><div class="content-writing-page"><div class="banner-area">
  <div class="container">
    <div class="row">
      <div class="col-md-6 align-self-center">
        <h1>Progress is impossible without content.</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In malesuada posuere metus
          a sollicitudin. Nam a felis tellus. Sed tempor at quam sed feugiat. Nunc sodales vel
          purus vel euismod.</p>
        <div class="mt-3">
          <button type="button" class="btn btn-primary rounded-pill mb-2">Go Pro</button>
          <button type="button" class="btn btn-outline-primary rounded-pill mb-2">Try it free</button>
        </div>
      </div>
      <div class="col-md-6 text-center">
        <img class="img-fluid" src="themes/canvas/images/contant-rewriting-img.svg" alt="Image Converter">
      </div>
    </div>
  </div>
</div>
[x-tool-form]
<div class="container content-writing-wrap">
  <div class="row">
    <div class="col-md-4">
      <div class="item">
        <div class="icon  align-self-center text-center">
          <svg viewBox="0 0 36 35" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M2.49351 31.4141C2.07273 32.0469 0.350649 34.4688 0 35H1.56623C1.56623 35 1.87013 34.5 3.77922 32.2734C4.45714 31.5078 14.8597 29.9609 18.1558 26.7031C20.1584 24.7344 20.5636 23.9062 22.1688 21.6641C22.3948 21.3594 30.7792 18.5 34.839 8C35.1195 7.42969 36.2494 4.3125 35.9377 0C32.9455 2.39062 19.9403 4.1875 18.1558 5.64844C17.3844 6.28125 16.2935 7.49219 16.1377 7.75C16.0597 7.89062 15.5922 8.63281 15.5922 8.63281C15.5922 8.63281 15.9584 7.15625 15.8961 6.90625C15.8727 6.82812 15.8494 6.65625 15.8416 6.64062C15.826 6.61719 15.4208 6.75781 15.3195 6.79688C12.226 7.89844 9.43636 10.5469 7.58182 12.9453C6.56883 14.2578 6.96623 16.8125 6.27273 18.1016C6.07792 18.4609 6.35844 14.1406 6.09351 14.4219C4.86234 15.7578 3.8961 16.8906 3.3974 17.625C-0.296104 23.1484 2.67273 31.1562 2.49351 31.4141ZM7.2 24.9766C8.46234 23.4609 9.95065 21.5312 11.9532 19.4453C13.4494 17.8984 20.3299 11.5938 21.2182 11.5234C21.2182 11.5234 20.6805 11.9844 20.2675 12.4062C19.5429 13.125 16.9247 15.25 14.2364 17.9688C13.5974 18.6172 12.2416 19.9531 10.9247 21.4531C9.85714 22.6719 8.7974 23.9922 7.96364 25.0156C5.5013 28.0625 4.01299 30.3281 3.97403 30.4219C3.9039 30.5391 3.69351 30.4062 3.65455 29.875C3.60779 29.5156 4.34805 28.4141 7.2 24.9766Z" />
          </svg>
        </div>
        <div class="content">
          <h2 class="title">Blog rewriting</h2>
          <p>Rem ipsam distinctio beatae dolore Doloremque ut.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="item">
        <div class="icon  align-self-center text-center">
          <svg viewBox="0 0 35 38" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M35 37.5034H10.2031L14.0547 31.8784H35V37.5034ZM22.4062 0.448738L2.17969 30.2847C1.44531 29.5269 0.976562 28.7691 0.84375 28.1519C0.828125 28.1284 0.859375 28.0659 0.90625 27.9956L1.00781 27.855C1.01562 27.8472 1.02344 27.8316 1.03125 27.8237C1.05469 27.7925 1.07031 27.7612 1.09375 27.7378L19.25 0.9253C19.5703 0.448738 20.0312 0.159675 20.625 0.0503003C21.1641 -0.0746997 21.7188 0.0346753 22.2734 0.3628L22.4062 0.448738ZM27.0625 6.03468L8.82812 32.855C8.66406 33.0659 8.5625 33.2456 8.21875 33.23C8.14844 33.2222 8.05469 33.23 7.96094 33.23C7.26562 33.2144 6.38281 32.9487 5.65625 32.6597L26.0156 2.72218L26.4922 3.10499C26.9531 3.44874 27.2422 3.90186 27.3672 4.45655C27.4687 5.03468 27.3672 5.56593 27.0625 6.03468ZM1.84375 34.6675C1.83594 34.4097 2.0625 31.9487 2.0625 31.9487C1.54688 31.5425 0.96875 30.7066 0.335938 29.4566L0 37.5034L7.33594 34.2066C6.03125 34.0972 5.07812 33.8394 4.49219 33.4409C4.49219 33.4409 2.65625 34.4253 1.84375 34.6675ZM25.3594 2.34718L4.99219 32.3237C4.71875 32.1831 4.4375 32.0269 4.14844 31.8472C4.14844 31.8472 4.14062 31.8472 4.13281 31.8394L4.125 31.8316C4.11719 31.8237 4.10938 31.8237 4.10156 31.8159C4.00781 31.7534 3.82031 31.6362 3.76562 31.5972C3.75781 31.5972 3.75781 31.5894 3.75 31.5894H3.74219C3.5 31.4253 2.96875 31.0347 2.75 30.8628L23.0312 0.847175L24.1484 1.56593L24.2109 1.60499L24.6016 1.85499L25.3594 2.34718Z" />
          </svg>
        </div>
        <div class="content">
          <h2 class="title">Content rewriting</h2>
          <p>Rem ipsam distinctio beatae dolore Doloremque ut.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="item">
        <div class="icon  align-self-center text-center">
          <svg viewBox="0 0 28 38" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M16.625 5.43068C8.12589 17.7008 9.23705 16.3654 0.729464 28.6356C0.0848213 29.5678 0 37.6219 0 37.6219C0 37.6219 7.53214 34.6992 8.1683 33.7754C16.871 21.2281 24.0723 10.5621 24.0723 10.5621L16.625 5.43068ZM27.8214 9.79787L20.3911 20.086C20.1366 20.4639 19.5513 20.5563 19.1781 20.2959C18.8134 20.0356 18.7116 19.4561 18.983 19.0781L25.9129 9.49553L25.1665 8.95803L24.5388 9.87346L17.1 4.74201L20.3571 0.0556841C20.5692 -0.238261 22.4013 0.660372 24.4625 2.07131C26.5237 3.48225 28.025 4.86799 27.8214 5.17033L26.1504 7.55549C26.1929 7.58068 26.6594 7.91662 27.6263 8.61369C27.8045 8.73967 27.9402 8.94123 27.9741 9.15119V9.15959C28.025 9.37795 27.9656 9.61311 27.8214 9.79787Z" />
          </svg>
        </div>
        <div class="content">
          <h2 class="title">Content rewriting</h2>
          <p>Rem ipsam distinctio beatae dolore Doloremque ut.</p>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="one-solution section-padding bg-light dark-mode-light-bg">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <div class="content sticky-md-top sticky-lg-top sticky-xl-top">
          <div class="hero-title bold pt-5">
            <h2 class="title"> One Solution for your all needs</h2>
            <p>Rem ipsam distinctio beatae dolore. Doloremque
              ut fugit molestiae accusamus optio maxime.
              Vel illo in qui laboriosam possimus ad rerum.
              <br><br>
              Rem ipsam distinctio beatae dolore. Doloremque
              ut fugit molestiae accusamus optio maxime.
              Vel illo in qui laboriosam possimus ad rerum.
              Rem ipsam distinctio beatae dolore. Doloremque
              ut fugit molestiae accusamus optio maxime.
              Vel illo in qui laboriosam possimus ad rerum.
              <br><br>
              Rem ipsam distinctio beatae dolore. Doloremque
              ut fugit molestiae accusamus optio maxime.
              Vel illo in qui laboriosam possimus ad rerum.
            </p>
          </div>

        </div>
      </div>
      <div class="col-md-8">
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="item">
              <div class="icon">
                <svg width="57" height="50" viewBox="0 0 57 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_753_3671)">
                    <path
                      d="M40.3711 3.18359C41.3184 3.18359 42.1094 3.87695 42.1094 4.85352V41.709C42.1094 42.793 41.3086 43.4277 40.3711 43.4277C39.0137 43.4277 37.4609 40.4883 37.2266 40.127C36.5234 39.1309 35.7715 38.1934 34.9609 37.334C32.5391 34.6973 30.0977 33.1641 27.6465 32.7539C24.3066 32.1387 20.4688 31.7285 16.1328 31.5039V35.6738C16.1328 36.7871 15.498 37.1777 15.3613 37.2559L14.5508 39.5996C14.2969 40.4395 14.707 42.1387 15.9668 42.1387C16.8848 42.1387 16.9141 42.4316 16.8555 43.0176L16.4551 46.1035C16.3965 46.6895 16.1328 46.9824 15.6445 46.9824H6.86523C5.3418 46.9824 5.43945 46.1621 5.57617 45.1367L7.43164 34.6387C7.61719 33.6426 6.93359 31.8359 5.61523 31.5137C5.3418 31.4551 2.70508 31.0645 1.57227 30.7422C0.644531 30.459 0 29.6094 0 28.6328V17.3145C0 16.1133 0.976562 15.1367 2.17773 15.1367H5.78125C6.16211 14.2773 6.77734 13.8477 7.63672 13.8477H14.9121C15.9961 13.8477 16.4355 14.502 16.6504 14.9707C16.6504 14.9707 23.9746 14.6094 27.6465 13.8477C30.0879 13.3398 32.5391 11.875 34.9609 9.26758C35.7715 8.4082 37.5879 5.91797 38.2324 4.85352C38.8477 3.74023 39.5605 3.18359 40.3711 3.18359ZM45.0977 27.5977V19.0137C45.0977 18.7598 45 18.5547 44.8145 18.3887C44.6289 18.2227 44.4238 18.1348 44.209 18.1348H43.6426C43.3984 18.1348 43.1934 18.2227 43.0371 18.3887C42.8711 18.5547 42.793 18.7598 42.793 19.0137V27.5977C42.793 27.8711 42.8711 28.0859 43.0371 28.2227C43.2031 28.3887 43.3984 28.4766 43.6426 28.4766H44.209C44.4238 28.4766 44.6289 28.3887 44.8145 28.2227C45 28.0859 45.0977 27.8809 45.0977 27.5977ZM47.1191 11.4746C47.0117 11.582 46.875 11.6406 46.7188 11.6406C46.5332 11.6699 46.3672 11.6113 46.2305 11.4746C46.1523 11.3672 46.1133 11.2207 46.1133 11.0547C46.1133 10.8887 46.1523 10.752 46.2305 10.6348L49.1797 7.2168C49.3164 7.12891 49.4727 7.08984 49.668 7.08984C49.8047 7.03125 49.9414 7.08008 50.0684 7.2168C50.2051 7.35352 50.2734 7.49023 50.2734 7.63672C50.2734 7.83203 50.2051 7.99805 50.0684 8.13477L47.1191 11.4746ZM50.0684 34.6777C50.2051 34.8145 50.2734 34.9805 50.2734 35.1758C50.2734 35.3418 50.2051 35.4785 50.0684 35.5957C49.9316 35.7031 49.7949 35.7617 49.668 35.7617C49.4824 35.7617 49.3164 35.6738 49.1797 35.5078L46.2305 32.1777C46.1523 32.0703 46.1133 31.9238 46.1133 31.7578C46.1133 31.6211 46.1523 31.4844 46.2305 31.3379C46.3672 31.2012 46.5234 31.1328 46.7188 31.1328C46.8848 31.1621 47.0117 31.2305 47.1191 31.3379L50.0684 34.6777ZM49.3848 15.6836C49.3848 15.4102 49.5215 15.2148 49.7852 15.0977L53.8281 13.4277C53.9941 13.3691 54.1504 13.3691 54.3164 13.4277C54.4824 13.4863 54.5898 13.5938 54.6387 13.7598C54.6875 13.9258 54.6875 14.082 54.6387 14.2188C54.5605 14.3848 54.4531 14.4922 54.3164 14.5508L50.1953 16.2598C50.0586 16.3184 49.9121 16.2988 49.7461 16.2207C49.6094 16.1621 49.4922 16.0547 49.3848 15.8887V15.6836ZM54.3066 28.2617C54.4434 28.3203 54.5508 28.418 54.6289 28.5547C54.6777 28.75 54.6777 28.916 54.6289 29.0527C54.5703 29.1895 54.4629 29.3066 54.3066 29.3848C54.1406 29.4434 53.9844 29.4434 53.8184 29.3848L49.7754 27.7148C49.502 27.627 49.375 27.4512 49.375 27.1777V26.9727C49.4824 26.7773 49.5996 26.6406 49.7363 26.5527C49.9023 26.4941 50.0488 26.4941 50.1856 26.5527L54.3066 28.2617ZM55.6445 20.8105C55.8105 20.8105 55.9375 20.8691 56.0449 20.9766C56.1816 21.084 56.25 21.2305 56.25 21.3965C56.25 21.5918 56.1816 21.748 56.0449 21.8555C55.9375 21.9629 55.8008 22.0215 55.6445 22.0215H51.2012C51.0645 22.0215 50.9277 21.9629 50.8008 21.8555C50.6641 21.748 50.5957 21.5918 50.5957 21.3965C50.5957 21.2305 50.6641 21.0938 50.8008 20.9766C50.9375 20.8691 51.0742 20.8105 51.2012 20.8105H55.6445Z" />
                  </g>
                  <defs>
                    <clipPath id="clip0_753_3671">
                      <rect width="56.25" height="50" fill="white" />
                    </clipPath>
                  </defs>
                </svg>
              </div>
              <div class="contant">
                <h2> Digital content</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry Lorem Ipsum.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="item">
              <div class="icon">
                <svg width="47" height="50" viewBox="0 0 47 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0_753_3677)">
                    <path
                      d="M19.3759 41.4844C19.8154 41.4844 20.2842 41.9531 20.2842 42.3926C20.2842 43.0566 19.747 43.3008 19.3759 43.3008C19.0049 43.3008 18.4677 43.0664 18.4677 42.3926C18.4775 41.9434 18.9463 41.4844 19.3759 41.4844ZM19.3759 37.8906C16.9638 37.8906 14.8935 39.9707 14.8935 42.3828C14.8935 44.9805 16.9638 46.875 19.3759 46.875C21.7881 46.875 23.8584 44.9707 23.8584 42.3828C23.8681 39.9707 21.7978 37.8906 19.3759 37.8906ZM32.3056 41.4844C32.7451 41.4844 33.2138 41.9531 33.2138 42.3926C33.2138 43.0566 32.6767 43.3008 32.3056 43.3008C31.9345 43.3008 31.3974 43.0664 31.3974 42.3926C31.4072 41.9434 31.8759 41.4844 32.3056 41.4844ZM32.3056 37.8906C29.8935 37.8906 27.8232 39.9707 27.8232 42.3828C27.8232 44.9805 29.8935 46.875 32.3056 46.875C34.7177 46.875 36.7881 44.9707 36.7881 42.3828C36.8076 39.9707 34.7373 37.8906 32.3056 37.8906ZM43.1845 25.2148L42.1396 29.7461H11.6904L5.14743 7.65625H2.26657C1.75876 7.65625 1.29001 7.49023 0.918918 7.20703C0.538059 6.92383 0.24509 6.52344 0.0986059 6.03516C-0.340847 4.58984 0.743137 3.125 2.26657 3.125H6.82712C7.93064 3.125 8.80954 3.90625 9.03415 4.87305V4.88281L15.0693 25.2051H43.1845V25.2148ZM44.5029 6.08398H14.7373C13.1455 6.08398 11.9931 7.62695 12.4521 9.16016C12.6084 9.6875 16.3291 22.1289 16.3291 22.1289H43.917C43.917 22.1289 46.622 9.67773 46.7783 9.16016C47.2373 7.61719 46.0849 6.08398 44.5029 6.08398ZM40.9482 35.0098C40.6748 36.0156 39.7666 36.7285 38.7217 36.7285H13.7509L13.0674 34.375H18.7998L18.3408 32.832H41.4756V32.8223C41.4756 32.8223 41.2217 33.9844 40.9482 35.0098Z" />
                  </g>
                  <defs>
                    <clipPath id="clip0_753_3677">
                      <rect width="46.875" height="50" />
                    </clipPath>
                  </defs>
                </svg>
              </div>
              <div class="contant">
                <h2>eCommerce content</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry Lorem Ipsum.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="item">
              <div class="icon">
                <svg width="46" height="50" viewBox="0 0 46 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M42.6094 38.9258C42.6094 43.3203 39.0645 46.875 34.6797 46.875C30.2949 46.875 26.75 43.3203 26.75 38.9258C26.75 38.6328 26.7695 38.3398 26.7988 38.0566L15.8027 31.1719C14.4355 32.2852 12.6973 32.9492 10.8125 32.9492C6.42773 32.9492 2.88281 29.3945 2.88281 25C2.88281 20.6055 6.42773 17.0508 10.8125 17.0508C12.6973 17.0508 14.4355 17.7051 15.8027 18.8184L26.8086 11.9336C26.7793 11.6504 26.7695 11.3574 26.7695 11.0742C26.7695 6.67969 30.3145 3.125 34.6992 3.125C39.084 3.125 42.6289 6.67969 42.6289 11.0742C42.6289 15.4688 39.084 19.0234 34.6992 19.0234C32.5801 19.0234 30.6465 18.1836 29.2207 16.8262L18.5859 23.457C18.6836 23.9551 18.7324 24.4629 18.7324 25C18.7324 25.5176 18.6836 26.0254 18.5859 26.5234L29.2109 33.1738C30.6367 31.8164 32.5508 30.9863 34.6797 30.9863C39.0645 30.9766 42.6094 34.5312 42.6094 38.9258Z" />
                </svg>
              </div>
              <div class="contant">
                <h2>Social Media content</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry Lorem Ipsum.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="item">
              <div class="icon">
                <svg width="55" height="50" viewBox="0 0 55 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M33.1641 43.2617H21.3281V45.6934H16.5918V48.125H37.9199V45.6934H33.1836L33.1641 43.2617ZM0 2.36328V39.9512C0 40.2246 0.214844 40.4395 0.488281 40.4395H54.0039C54.2773 40.4395 54.4922 40.2246 54.4922 39.9512V2.36328C54.4922 2.08984 54.2773 1.875 54.0039 1.875H0.488281C0.214844 1.875 0 2.08984 0 2.36328ZM49.0039 39.6973C48.2617 39.6973 47.666 39.082 47.666 38.3105C47.666 37.5488 48.2617 36.9238 49.0039 36.9238C49.7363 36.9238 50.3418 37.5391 50.3418 38.3105C50.3418 39.0723 49.7461 39.6973 49.0039 39.6973ZM52.1191 35.957H2.37305V4.31641H52.1289V35.957H52.1191ZM49.7754 6.66016H4.7168V33.623H49.7852L49.7754 6.66016Z" />
                </svg>
              </div>
              <div class="contant">
                <h2>Website content</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry Lorem Ipsum.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="item">
              <div class="icon">
                <svg width="44" height="50" viewBox="0 0 44 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M40.9082 3.125H2.83203C1.26953 3.125 0 4.38477 0 5.94727V34.5508C0 36.1133 1.26953 37.373 2.83203 37.373H7.11914L6.14258 43.7988C5.83008 46.3477 8.90625 47.8711 10.7617 46.0938L21.6016 37.3828H40.918C42.4805 37.3828 43.75 36.123 43.75 34.5605V5.95703C43.75 4.38477 42.4805 3.125 40.9082 3.125ZM32.1777 26.6699H18.9551V22.2266H32.1777V26.6699ZM32.1875 18.3887H11.8848V13.9453H32.1875V18.3887Z" />
                </svg>
              </div>
              <div class="contant">
                <h2>Blog content</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry Lorem Ipsum.</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="item">
              <div class="icon">
                <svg width="47" height="50" viewBox="0 0 47 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M46.8561 20.9375L46.319 13.252C45.9283 7.69532 41.6608 3.19337 36.1627 2.53907L28.4967 1.6211C26.0748 1.32813 23.6627 2.13868 21.8951 3.8379L0.830688 24.0723C-0.24353 25.1074 -0.282593 26.8067 0.752563 27.8906L19.4928 47.5977C20.5182 48.6816 22.2272 48.7109 23.2916 47.6758L44.3658 27.4414C46.1041 25.752 47.0221 23.3789 46.8561 20.9375ZM41.1627 7.95899C43.1647 10.0684 43.0963 13.4082 40.9967 15.4199C38.8971 17.4316 35.5768 17.3633 33.5748 15.2539C31.5729 13.1445 31.6412 9.8047 33.7408 7.79298C35.8307 5.78126 39.1608 5.84962 41.1627 7.95899Z" />
                </svg>
              </div>
              <div class="contant">
                <h2>Sales content</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry Lorem Ipsum.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div></div></div>';
        return $data;
    }
}
