
<script src="https://cdn.babylonjs.com/babylon.js"></script>
<script src="https://preview.babylonjs.com/loaders/babylonjs.loaders.min.js"></script>
<script src="https://cdn.babylonjs.com/gui/babylon.gui.js"></script>
<style>
    #renderCanvas {
        width: 100%;
        height: 100%;
        touch-action: none;
        border: none;
        aspect-ratio: 3/4;
    }

    #renderCanvas:focus-visible {
        outline: none;
    }
</style>
<canvas id="renderCanvas"></canvas>
<script>
    const canvas = document.getElementById("renderCanvas"); // Get the canvas element
    const engine = new BABYLON.Engine(canvas, true); // Generate the BABYLON 3D engine
    const color_gray_3 = new BABYLON.Color3(0.83, 0.83, 0.83);
    const color_bege = new BABYLON.Color3(0.93, 0.85, 0.69);
    const color_metal = new BABYLON.Color3(0.47, 0.47, 0.47);
    const color_black = new BABYLON.Color3(0.09, 0.09, 0.09);
    const element_width = document.querySelector('#renderCanvas').offsetWidth
    const element_height = document.querySelector('#renderCanvas').offsetHeight

    const createScene = function() {
        // This creates a basic Babylon Scene object (non-mesh)
        const scene = new BABYLON.Scene(engine);

        // Background color
        scene.clearColor = new BABYLON.Color4(0, 0, 0, 0);

        // const camera = new BABYLON.UniversalCamera("camera1", new BABYLON.Vector3(0, 0, -10), scene)
        const camera = new BABYLON.ArcRotateCamera("camera1", 0, 0, 15, new BABYLON.Vector3(0, 0, 0), scene)

        // This targets the camera to scene origin
        camera.setTarget(BABYLON.Vector3.Zero());

        // Positions the camera overwriting alpha, beta, radius
        camera.setPosition(new BABYLON.Vector3(0, 0, 20));

        // This attaches the camera to the canvas
        camera.attachControl(canvas, true);

        // Trava zoom
        camera.lowerRadiusLimit = 10
        camera.upperRadiusLimit = 2

        const light2 = new BABYLON.HemisphericLight("light", new BABYLON.Vector3(0, 0, Math.PI), scene);
        const light3 = new BABYLON.HemisphericLight("light", new BABYLON.Vector3(0, Math.PI, 0), scene);
        const light4 = new BABYLON.HemisphericLight("light", new BABYLON.Vector3(0, 0, Math.PI), scene);

        light2.groundColor = color_bege
        light3.groundColor = color_bege

        // Default intensity is 1. Let's dim the light a small amount
        light2.intensity = .3;
        light3.intensity = .3;
        light4.intensity = .5;

        importFamilia(scene)
        // const sphere = BABYLON.MeshBuilder.CreateSphere("sphere", {});

        return scene;
    };

    const scene = createScene(); //Call the createScene function

    // Register a render loop to repeatedly render the scene
    engine.runRenderLoop(function() {
        scene.render();
    });

    // Watch for browser/canvas resize events
    window.addEventListener("resize", function() {
        engine.resize();
    });

    function convertPxToPercent(lar_to_page, percent) {
        return lar_to_page * percent / 100
    }

    async function importFamilia(scene) {
        try {
            const result = await BABYLON.SceneLoader.ImportMeshAsync('', '{{ asset('3d')}}/', 'familia.glb', scene);

            const {
                meshes,
                particleSystems,
                skeletons,
                animationGroups,
                transformNodes
            } = result;

            const material_product = new BABYLON.StandardMaterial("material_product", scene);
            material_product.diffuseColor = color_black;

            const material_fio = new BABYLON.StandardMaterial(scene);
            material_fio.diffuseColor = color_black;

            const material_resistencia = new BABYLON.StandardMaterial(scene);
            material_resistencia.diffuseColor = color_metal;

            meshes[0].rotation = new BABYLON.Vector3(BABYLON.Space.LOCAL, -3, BABYLON.Space.LOCAL)

            meshes.forEach(mesh => {
                if (mesh.name == 'aquecedor') {
                    mesh.material = material_product
                }

                if (
                    mesh.name == 'saida-fio' ||
                    mesh.name == 'caixa' ||
                    mesh.name == 'Fio'
                ) {
                    mesh.material = material_fio
                }

                if (
                    mesh.name == 'resistencia_cima' ||
                    mesh.name == 'resistencia_meio' ||
                    mesh.name == 'resistencia_baixo'
                ) {
                    mesh.material = material_resistencia
                }

                if (
                    mesh.name == 'plug-fase' ||
                    mesh.name == 'plug-neutro'
                ) {
                    mesh.material = material_resistencia
                }
            });

            // // GUI
            // var advancedTexture = BABYLON.GUI.AdvancedDynamicTexture.CreateFullscreenUI("UI");

            // let bt_width = convertPxToPercent(element_width, 10) + "px"
            // let bt_border = 1.5

            // var bt_black = new BABYLON.GUI.Ellipse();
            // bt_black.width = bt_width
            // bt_black.height = bt_width
            // bt_black.color = "#000";
            // bt_black.background = "#000";
            // bt_black.leftInPixels = 0
            // bt_black.topInPixels = convertPxToPercent(element_height, 50) * -1;
            // bt_black.thickness = bt_border

            // bt_black.onPointerUpObservable.add(function() {
            //     material_product.diffuseColor = new BABYLON.Color3(0.09, 0.09, 0.09);
            //     material_fio.diffuseColor = new BABYLON.Color3(0.09, 0.09, 0.09);
            // });
            // advancedTexture.addControl(bt_black);

            // var bt_white = new BABYLON.GUI.Ellipse();
            // bt_white.width = bt_width
            // bt_white.height = bt_width
            // bt_white.color = "#000";
            // bt_white.background = "#fff";
            // bt_white.leftInPixels = convertPxToPercent(element_width, 15);
            // bt_white.topInPixels = convertPxToPercent(element_height, 50) * -1;
            // bt_white.thickness = bt_border

            // bt_white.onPointerUpObservable.add(function() {
            //     material_product.diffuseColor = new BABYLON.Color3(0.91, 0.91, 0.91);
            //     material_fio.diffuseColor = new BABYLON.Color3(0.91, 0.91, 0.91);
            // });
            // advancedTexture.addControl(bt_white);

            // var bt_bege = new BABYLON.GUI.Ellipse();
            // bt_bege.width = bt_width
            // bt_bege.height = bt_width
            // bt_bege.color = "#000";
            // bt_bege.background = "#f8e0b2";
            // bt_bege.leftInPixels = convertPxToPercent(element_width, 15) * -1;
            // bt_bege.topInPixels = convertPxToPercent(element_height, 50) * -1;
            // bt_bege.thickness = bt_border

            // bt_bege.onPointerUpObservable.add(function() {
            //     material_product.diffuseColor = new BABYLON.Color3(0.93, 0.85, 0.69);
            //     material_fio.diffuseColor = new BABYLON.Color3(0.91, 0.91, 0.91);
            // });
            // advancedTexture.addControl(bt_bege);
        } catch (e) {
            console.log('error', e);
        }
    }
</script>
