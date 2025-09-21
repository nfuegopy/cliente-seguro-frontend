<script setup>
import Button from "primevue/button";
</script>

<template>
    <section
        class="min-h-screen w-full bg-gray-800 text-white py-20 services-section-container"
    >
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-16 text-teal-400">
                Nuestros Servicios
            </h2>

            <div
                class="service-section flex flex-col md:flex-row items-center gap-12 mb-20 md:mb-32"
            >
                <img
                    src="/img/demo-imagen-seguro-medico.png"
                    alt="Seguro Médico"
                    class="w-full md:w-1/2 rounded-xl shadow-2xl transform transition-transform hover:scale-105 duration-300"
                />
                <div class="w-full md:w-1/2 text-center md:text-left">
                    <h3 class="text-4xl font-bold mb-4">Seguro Médico</h3>
                    <p class="text-lg text-gray-300 mb-6 leading-relaxed">
                        La mejor cobertura para tu salud y la de tu familia. Te
                        ofrecemos una red de hospitales y médicos de primera.
                    </p>
                    <Button
                        label="Ver Detalles"
                        size="large"
                        class="!bg-teal-500 hover:!bg-teal-600 !border-teal-500 !font-semibold"
                    />
                </div>
            </div>

            <div
                class="service-section flex flex-col md:flex-row-reverse items-center gap-12 mb-20 md:mb-32"
            >
                <img
                    src="/img/demo-imagen-seguro-vehiculo.png"
                    alt="Seguro de Vehículo"
                    class="w-full md:w-1/2 rounded-xl shadow-2xl transform transition-transform hover:scale-105 duration-300"
                />
                <div class="w-full md:w-1/2 text-center md:text-right">
                    <h3 class="text-4xl font-bold mb-4">Seguro de Vehículo</h3>
                    <p class="text-lg text-gray-300 mb-6 leading-relaxed">
                        Protege tu inversión con nuestra amplia gama de
                        coberturas, desde daños a terceros hasta robo total.
                    </p>
                    <Button
                        label="Ver Detalles"
                        size="large"
                        class="!bg-teal-500 hover:!bg-teal-600 !border-teal-500 !font-semibold"
                    />
                </div>
            </div>

            <div
                class="service-section flex flex-col md:flex-row items-center gap-12"
            >
                <img
                    src="/img/demo-imagen-logo-inicial.png"
                    alt="Seguro de Hogar"
                    class="w-full md:w-1/2 rounded-xl shadow-2xl transform transition-transform hover:scale-105 duration-300"
                />
                <div class="w-full md:w-1/2 text-center md:text-left">
                    <h3 class="text-4xl font-bold mb-4">Seguro de Hogar</h3>
                    <p class="text-lg text-gray-300 mb-6 leading-relaxed">
                        Tu hogar es tu santuario. Nosotros nos encargamos de
                        protegerlo de imprevistos como incendios o robos.
                    </p>
                    <Button
                        label="Ver Detalles"
                        size="large"
                        class="!bg-teal-500 hover:!bg-teal-600 !border-teal-500 !font-semibold"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
