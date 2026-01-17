<div id="home-video">
    <video width="100%" height="auto" loop autoplay muted>
        {{-- <source src="https://ik.imagekit.io/moltech/natal-2023.mp4?updatedAt=1703167630131" type="video/mp4"> --}}
        <source src="https://ik.imagekit.io/moltech/beneficios-ver%C3%A3o-completo-720p.mp4?updatedAt=1708110973639" type="video/mp4">
        {{-- <source src="movie.ogg" type="video/ogg"> --}}
        Seu navegador não suporta vídeos
    </video>
    <div class="controls">
        <div class="bt-muted" onclick="molMuted(this, this.parentElement.previousElementSibling)">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M6 7l8-5v20l-8-5v-10zm-6 10h4v-10h-4v10zm20.264-13.264l-1.497 1.497c1.847 1.783 2.983 4.157 2.983 6.767 0 2.61-1.135 4.984-2.983 6.766l1.498 1.498c2.305-2.153 3.735-5.055 3.735-8.264s-1.43-6.11-3.736-8.264zm-.489 8.264c0-2.084-.915-3.967-2.384-5.391l-1.503 1.503c1.011 1.049 1.637 2.401 1.637 3.888 0 1.488-.623 2.841-1.634 3.891l1.503 1.503c1.468-1.424 2.381-3.309 2.381-5.394z" />
                </svg>
            </span>
            <span style="display: none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M5 17h-5v-10h5v10zm2-10v10l9 5v-20l-9 5zm15.324 4.993l1.646-1.659-1.324-1.324-1.651 1.67-1.665-1.648-1.316 1.318 1.67 1.657-1.65 1.669 1.318 1.317 1.658-1.672 1.666 1.653 1.324-1.325-1.676-1.656z" />
                </svg>
            </span>
        </div>
        <div class="bt-play" onclick="molPlayPause(this, this.parentElement.previousElementSibling)">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M11 22h-4v-20h4v20zm6-20h-4v20h4v-20z" />
                </svg>
            </span>
            <span style="display: none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M3 22v-20l18 10-18 10z" />
                </svg>
            </span>
        </div>
    </div>
</div>

<style>
    #home-video {
        max-width: 1550px;
        margin: auto;
        position: relative;

        >.controls {
            position: absolute;
            right: 0;
            top: 0;
            display: flex;
            gap: 10px;

            >.bt-muted,
            >.bt-play {
                padding: 10px;

                >span {
                    >svg {
                        filter: invert(1)
                    }
                }
            }
        }
    }
</style>

<script>
    function molMuted(e, tg) {
        if(tg.muted) {
            e.children[0].style.display = 'none'
            e.children[1].style.display = 'initial'
        } else {
            e.children[0].style.display = 'initial'
            e.children[1].style.display = 'none'
        }
        tg.muted = !tg.muted
    }

    function molPlayPause(e, tg) {
        if (tg.paused) {
            tg.play()
            e.children[0].style.display = 'initial'
            e.children[1].style.display = 'none'
        } else {
            tg.pause()

            e.children[0].style.display = 'none'
            e.children[1].style.display = 'initial'
        }
    }
</script>
