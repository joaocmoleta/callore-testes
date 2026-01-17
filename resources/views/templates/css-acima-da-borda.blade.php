<style>
    body {
        margin: 0;
        padding: 0;
    }

    .loader-box-page {
        background: #fff;
        height: 100vh;
        position: fixed;
        z-index: 15;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .loader-page {
        width: 48px;
        height: 48px;
        border: 5px solid #ccc;
        border-bottom-color: transparent;
        border-radius: 50%;
        display: inline-block;
        box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }

    @keyframesrotation {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
