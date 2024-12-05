@if (session()->has('error'))
    <div class="alert alert-danger d-flex" role="alert">
        {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current m-0.5" viewBox="0 0 256 256"><rect width="50" height="50" fill="none"></rect><path d="M225.9,102.8c-3.8-3.9-7.7-8-9.2-11.5s-1.4-8.7-1.5-14c-.1-9.7-.3-20.8-8-28.5s-18.8-7.9-28.5-8c-5.3-.1-10.7-.2-14-1.5s-7.6-5.4-11.5-9.2C146.3,23.5,138.4,16,128,16s-18.3,7.5-25.2,14.1c-3.9,3.8-8,7.7-11.5,9.2s-8.7,1.4-14,1.5c-9.7.1-20.8.3-28.5,8s-7.9,18.8-8,28.5c-.1,5.3-.2,10.7-1.5,14s-5.4,7.6-9.2,11.5C23.5,109.7,16,117.6,16,128s7.5,18.3,14.1,25.2c3.8,3.9,7.7,8,9.2,11.5s1.4,8.7,1.5,14c.1,9.7.3,20.8,8,28.5s18.8,7.9,28.5,8c5.3.1,10.7.2,14,1.5s7.6,5.4,11.5,9.2c6.9,6.6,14.8,14.1,25.2,14.1s18.3-7.5,25.2-14.1c3.9-3.8,8-7.7,11.5-9.2s8.7-1.4,14-1.5c9.7-.1,20.8-.3,28.5-8s7.9-18.8,8-28.5c.1-5.3.2-10.7,1.5-14s5.4-7.6,9.2-11.5c6.6-6.9,14.1-14.8,14.1-25.2S232.5,109.7,225.9,102.8ZM120,80a8,8,0,0,1,16,0v56a8,8,0,0,1-16,0Zm8,104a12,12,0,1,1,12-12A12,12,0,0,1,128,184Z"></path></svg> --}}
        <div class="flex flex-col">
            <span class="ml-2 font-semibold">
            </span>
            <span class="ml-1">
                <li>
                {{ session()->get('error') }}</li>
            </span>
        </div>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success d-flex" role="alert" id="success-alert">
        {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-2 h-2 fill-current m-0.5" viewBox="0 0 256 256"><rect width="50" height="50" fill="none"></rect><path d="M128,24A104,104,0,1,0,232,128,104.2,104.2,0,0,0,128,24Zm49.5,85.8-58.6,56a8.1,8.1,0,0,1-5.6,2.2,7.7,7.7,0,0,1-5.5-2.2l-29.3-28a8,8,0,1,1,11-11.6l23.8,22.7,53.2-50.7a8,8,0,0,1,11,11.6Z"></path></svg> --}}
        <span class="ml-2 font-semibold">
            Berhasil:
        </span>
        <span class="ml-1">
            {{ session()->get('success') }}
        </span>
    </div>
@endif

@if (session()->has('warning'))
    <div class="alert alert-warning d-flex" role="alert">
        {{-- <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current m-0.5" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M236.7,188,148.8,36a24,24,0,0,0-41.6,0h0L19.3,188A23.9,23.9,0,0,0,40,224H216a23.9,23.9,0,0,0,20.7-36ZM120,104a8,8,0,0,1,16,0v40a8,8,0,0,1-16,0Zm8,88a12,12,0,1,1,12-12A12,12,0,0,1,128,192Z"></path></svg> --}}
        <span class="ml-2 font-semibold">
            Peringatan:
        </span>
        <span class="ml-1">
            {{ session()->get('warning') }}
        </span>
    </div>
@endif

@if (session()->has('info'))
    <div class="alert alert-info d-flex" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current m-0.5" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M128,24A104,104,0,1,0,232,128,104.2,104.2,0,0,0,128,24Zm-2,48a12,12,0,1,1-12,12A12,12,0,0,1,126,72Zm10,112h-8a8,8,0,0,1-8-8V128a8,8,0,0,1,0-16h8a8,8,0,0,1,8,8v48a8,8,0,0,1,0,16Z"></path></svg>
        <span class="ml-2 font-semibold">
            Informasi:
        </span>
        <span class="ml-1">
            {{ session()->get('info') }}
        </span>
    </div>
@endif

<script>
    window.setTimeout(function() {
        const successAlert = document.getElementById('success-alert');
        const errorAlert = document.getElementById('error-alert');

        if (successAlert) {
            successAlert.classList.add('fade-out');
            setTimeout(function() {
                successAlert.remove();
            }, 500);
        }

        if (errorAlert) {
            errorAlert.classList.add('fade-out');
            setTimeout(function() {
                errorAlert.remove();
            }, 500);
        }
    }, 5000);
</script>
<style>
    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease-out;
    }
</style>
