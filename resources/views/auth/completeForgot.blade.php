@vite('resources/js/app.js')
<div class="h-screen bg-white flex flex-col space-y-10 justify-center items-center">
    <div class="bg-white w-96 shadow-xl rounded p-5">
        <h1 class="text-3xl font-medium flex justify-center items-center">Успешно</h1>
        <h4 class="text-3xs font-medium flex justify-center items-center p-4">Письмо было отправлено на вашу
            почту</h4>
        <div class="flex justify-center items-center">
            <a href="{{ route('auth.login') }}" class="font-medium text-blue-900 hover:bg-blue-300 rounded-md p-2">Авторизоваться</a>
        </div>
    </div>
</div>
