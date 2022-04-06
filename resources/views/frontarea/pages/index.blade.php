{{-- Master Layout --}}
@extends('cortex/foundation::frontarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@section('body-attributes')data-spy="scroll" data-offset="0" data-target="#navigation"@endsection

{{-- Main Content --}}
@section('content')

    <!-- Hero section -->
    <div class="relative">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative shadow-xl sm:rounded-2xl sm:overflow-hidden">
                <div class="absolute inset-0">
                    <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1521737852567-6949f3f9f2b5?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=2830&q=80&sat=-100" alt="People working on laptops">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-800 to-indigo-700 mix-blend-multiply"></div>
                </div>
                <div class="relative px-4 py-16 sm:px-6 sm:py-24 lg:py-32 lg:px-8">
                    <h1 class="text-center text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                        <span class="block text-white">Welcome To <b>Homepage</b></span>
                    </h1>
                    <p class="mt-6 max-w-lg mx-auto text-center text-xl text-indigo-200 sm:max-w-3xl">Show your product with this handsome theme.</p>
                    <div class="col-">

                    </div>
                    <div class="mt-10 flex justify-between">
                        <div class="lg:w-1/3 md:w-1/2 text-white">
                            <h5 class="text-white">Amazing Results</h5>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <img class="hidden-xs hidden-sm hidden-md" src="img/arrow1.png">
                        </div>
                        <div class="lg:w-1/3 md:w-1/2 text-white">
                            <img class="hidden-xs hidden-sm hidden-md" src="img/arrow2.png">
                            <h5 class="text-white">Awesome Design</h5>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-100">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
                <h1 class="text-center text text-4xl">Designed To Excel</h1>
                <div class="mt-6 grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="col-span-1 flex flex-col justify-center text-center md:col-span-2 lg:col-span-1">
                        <img src="img/intro01.png" alt="">
                        <h3 class="text-2xl">Community</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    </div>
                    <div class="col-span-1 flex flex-col justify-center text-center md:col-span-2 lg:col-span-1">
                        <img src="img/intro02.png" alt="">
                        <h3 class="text-2xl">Schedule</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    </div>
                    <div class="col-span-1 flex flex-col justify-center text-center md:col-span-2 md:col-start-2 lg:col-span-1">
                        <img src="img/intro03.png" alt="">
                        <h3 class="text-2xl">Monitoring</h3>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alternating Feature Sections -->
        <div class="relative pt-16 pb-32 overflow-hidden">
            <h1 class="text-center text text-4xl mb-4">What's New?</h1>
            <div class="relative">
                <div class="lg:mx-auto lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-2 lg:grid-flow-col-dense lg:gap-24">
                    <div class="px-4 max-w-xl mx-auto sm:px-6 lg:py-16 lg:max-w-none lg:mx-0 lg:px-0">
                        <div class="px-4 max-w-xl mx-auto sm:px-6 lg:py-16 lg:max-w-none lg:mx-0 lg:px-0 accordion" id="accordion2">
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle text-3xl font-extrabold tracking-tight text-gray-800" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                                        First Class Design
                                    </a>
                                </div>
                                <div id="collapseOne" class="accordion-body collapse in">
                                    <div class="accordion-inner mt-4 text-lg text-gray-500">
                                        <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle text-3xl font-extrabold tracking-tight text-gray-800" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                                        Retina Ready Theme
                                    </a>
                                </div>
                                <div id="collapseTwo" class="accordion-body collapse">
                                    <div class="accordion-inner mt-4 text-lg text-gray-500">
                                        <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle text-3xl font-extrabold tracking-tight text-gray-800" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                                        Awesome Support
                                    </a>
                                </div>
                                <div id="collapseThree" class="accordion-body collapse">
                                    <div class="accordion-inner mt-4 text-lg text-gray-500">
                                        <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a class="accordion-toggle text-3xl font-extrabold tracking-tight text-gray-800" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour">
                                        Responsive Design
                                    </a>
                                </div>
                                <div id="collapseFour" class="accordion-body collapse">
                                    <div class="accordion-inner mt-4 text-lg text-gray-500">
                                        <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-12 sm:mt-16 lg:mt-0">
                        <div class="pl-4 -mr-48 sm:pl-6 md:-mr-16 lg:px-0 lg:m-0 lg:relative lg:h-full">
{{--                            <img class="w-full rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 lg:absolute lg:left-0 lg:h-full lg:w-auto lg:max-w-none" src="https://tailwindui.com/img/component-images/inbox-app-screenshot-1.jpg" alt="Inbox user interface">--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white">
        <div class="max-w-7xl mx-auto pt-16 pb-8 px-4 sm:px-6 lg:pt-24 lg:px-8">
            <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                <div class="grid grid-cols-2 gap-8 xl:col-span-2">
                    <div class="md:grid md:grid-cols-2 md:gap-8">
                        <div>
                            <h3 class="text-2xl">Address</h3>
                            <p>
                                Av. Greenville 987,<br/>
                                New York,<br/>
                                90873<br/>
                                United States
                            </p>
                        </div>
                    </div>
                    <div class="md:grid md:grid-cols-2 md:gap-8">

                    </div>
                </div>
                <div class="mt-12 xl:mt-0">
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Drop Us A Line</h3>

                    <form class="mt-4 sm:flex sm:flex-col sm:max-w-md" role="form" action="#" method="post" enctype="plain">
                        <div>
                            <label class="sr-only" for="name1">Your Name</label>
                            <input type="name" name="Name" class="appearance-none min-w-0 w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 text-base text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:placeholder-gray-400" id="name1" placeholder="Your Name">
                        </div>
                        <div class="mt-3">
                            <label class="sr-only" for="email1">Email address</label>
                            <input type="email" name="Mail" class="appearance-none min-w-0 w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 text-base text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:placeholder-gray-400" id="email1" placeholder="Enter email">
                        </div>
                        <div class="mt-3">
                            <label class="sr-only">Your Text</label>
                            <textarea class="appearance-none min-w-0 w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 text-base text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:placeholder-gray-400" name="Message" rows="3"></textarea>
                        </div>
                        <div class="mt-3 rounded-md">
                            <button type="submit" class="w-full flex items-center justify-center bg-gradient-to-r from-purple-600 to-indigo-600 bg-origin-border px-4 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white hover:from-purple-700 hover:to-indigo-700">SUBMIT</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
