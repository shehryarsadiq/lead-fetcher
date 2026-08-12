document.addEventListener("DOMContentLoaded", () => {

    const container = document.getElementById("webgl-bg");

    if (!container || typeof THREE === "undefined") {
        return;
    }

    const scene = new THREE.Scene();

    const camera = new THREE.PerspectiveCamera(
        55,
        window.innerWidth / window.innerHeight,
        0.1,
        100
    );

    camera.position.set(0, 0, 7);

    const renderer = new THREE.WebGLRenderer({
        alpha: true,
        antialias: true
    });

    renderer.setPixelRatio(
        Math.min(window.devicePixelRatio, 2)
    );

    renderer.setSize(
        window.innerWidth,
        window.innerHeight
    );

    container.appendChild(renderer.domElement);


    /*
    ==========================================
    PARTICLE FIELD
    ==========================================
    */

    const particleCount = 500;

    const particlePositions = new Float32Array(
        particleCount * 3
    );

    for (let i = 0; i < particleCount; i++) {

        particlePositions[i * 3] =
            (Math.random() - 0.5) * 18;

        particlePositions[i * 3 + 1] =
            (Math.random() - 0.5) * 10;

        particlePositions[i * 3 + 2] =
            (Math.random() - 0.5) * 8;

    }

    const particleGeometry =
        new THREE.BufferGeometry();

    particleGeometry.setAttribute(
        "position",
        new THREE.BufferAttribute(
            particlePositions,
            3
        )
    );

    const particleMaterial =
        new THREE.PointsMaterial({
            color: 0x7867ff,
            size: 0.025,
            transparent: true,
            opacity: 0.65,
            depthWrite: false
        });

    const particles =
        new THREE.Points(
            particleGeometry,
            particleMaterial
        );

    scene.add(particles);


    /*
    ==========================================
    MAIN WEB3 SPHERE
    ==========================================
    */

    const sphereGeometry =
        new THREE.IcosahedronGeometry(
            2.25,
            2
        );

    const sphereMaterial =
        new THREE.MeshBasicMaterial({
            color: 0x7867ff,
            wireframe: true,
            transparent: true,
            opacity: 0.12
        });

    const sphere =
        new THREE.Mesh(
            sphereGeometry,
            sphereMaterial
        );

    sphere.position.set(
        2.2,
        0,
        -1
    );

    sphere.rotation.x = 0.5;

    scene.add(sphere);


    /*
    ==========================================
    INNER SPHERE
    ==========================================
    */

    const innerGeometry =
        new THREE.IcosahedronGeometry(
            1.7,
            1
        );

    const innerMaterial =
        new THREE.MeshBasicMaterial({
            color: 0x9b8fff,
            wireframe: true,
            transparent: true,
            opacity: 0.08
        });

    const innerSphere =
        new THREE.Mesh(
            innerGeometry,
            innerMaterial
        );

    innerSphere.position.copy(
        sphere.position
    );

    scene.add(innerSphere);


    /*
    ==========================================
    ORBIT RINGS
    ==========================================
    */

    const ringGroup =
        new THREE.Group();

    ringGroup.position.copy(
        sphere.position
    );

    scene.add(ringGroup);


    const ringMaterial =
        new THREE.MeshBasicMaterial({
            color: 0x7867ff,
            transparent: true,
            opacity: 0.18
        });


    const ring1 =
        new THREE.Mesh(
            new THREE.TorusGeometry(
                2.8,
                0.008,
                8,
                160
            ),
            ringMaterial
        );

    ring1.rotation.x =
        Math.PI / 2.5;

    ringGroup.add(ring1);


    const ring2 =
        new THREE.Mesh(
            new THREE.TorusGeometry(
                3.1,
                0.006,
                8,
                160
            ),
            ringMaterial
        );

    ring2.rotation.y =
        Math.PI / 2.4;

    ring2.rotation.z =
        0.4;

    ringGroup.add(ring2);


    const ring3 =
        new THREE.Mesh(
            new THREE.TorusGeometry(
                3.3,
                0.004,
                8,
                160
            ),
            ringMaterial
        );

    ring3.rotation.x =
        0.8;

    ring3.rotation.z =
        1.1;

    ringGroup.add(ring3);


    /*
    ==========================================
    SMALL ORBITING NODES
    ==========================================
    */

    const nodeGroup =
        new THREE.Group();

    nodeGroup.position.copy(
        sphere.position
    );

    scene.add(nodeGroup);


    const nodeGeometry =
        new THREE.SphereGeometry(
            0.045,
            8,
            8
        );

    const nodeMaterial =
        new THREE.MeshBasicMaterial({
            color: 0x55e6a5
        });


    for (let i = 0; i < 9; i++) {

        const node =
            new THREE.Mesh(
                nodeGeometry,
                nodeMaterial
            );

        const angle =
            (Math.PI * 2 / 9) * i;

        const radius =
            2.7 + Math.random() * 0.7;

        node.position.x =
            Math.cos(angle) * radius;

        node.position.y =
            Math.sin(angle) * radius;

        node.position.z =
            (Math.random() - 0.5) * 1.2;

        nodeGroup.add(node);

    }


    /*
    ==========================================
    MOUSE PARALLAX
    ==========================================
    */

    let mouseX = 0;
    let mouseY = 0;

    window.addEventListener(
        "mousemove",
        event => {

            mouseX =
                (event.clientX /
                    window.innerWidth) -
                0.5;

            mouseY =
                (event.clientY /
                    window.innerHeight) -
                0.5;

        }
    );


    /*
    ==========================================
    ANIMATION
    ==========================================
    */

    const clock =
        new THREE.Clock();


    function animate() {

        requestAnimationFrame(
            animate
        );

        const elapsed =
            clock.getElapsedTime();


        particles.rotation.y =
            elapsed * 0.015;

        particles.rotation.x =
            elapsed * 0.004;


        sphere.rotation.x +=
            0.0008;

        sphere.rotation.y +=
            0.0015;


        innerSphere.rotation.x -=
            0.001;

        innerSphere.rotation.y -=
            0.0012;


        ringGroup.rotation.y +=
            0.0018;

        ringGroup.rotation.z +=
            0.0008;


        nodeGroup.rotation.y +=
            0.0025;


        /*
        Mouse movement
        */

        camera.position.x +=
            (
                mouseX * 0.35 -
                camera.position.x
            ) * 0.025;

        camera.position.y +=
            (
                -mouseY * 0.25 -
                camera.position.y
            ) * 0.025;

        camera.lookAt(
            0,
            0,
            0
        );


        renderer.render(
            scene,
            camera
        );

    }


    animate();


    /*
    ==========================================
    RESPONSIVE
    ==========================================
    */

    window.addEventListener(
        "resize",
        () => {

            camera.aspect =
                window.innerWidth /
                window.innerHeight;

            camera.updateProjectionMatrix();

            renderer.setSize(
                window.innerWidth,
                window.innerHeight
            );

            renderer.setPixelRatio(
                Math.min(
                    window.devicePixelRatio,
                    2
                )
            );

        }
    );

});