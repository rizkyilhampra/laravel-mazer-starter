import { defineConfig, normalizePath } from "vite";
import laravel from "laravel-vite-plugin";
import path, { resolve } from "path";

const __dirname = path.dirname(new URL(import.meta.url).pathname);

export default defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/js/app.js",
        "resources/js/initTheme.js",
        "resources/js/components/dark.js",
        "resources/js/mazer.js",
        "resources/scss/pages/auth.scss",
      ],
      refresh: true,
    }),
  ],
  resolve: {
    alias: {
      "@": normalizePath(resolve(__dirname, "resources")),
      "~bootstrap": resolve(__dirname, "node_modules/bootstrap"),
      "~bootstrap-icons": resolve(__dirname, "node_modules/bootstrap-icons"),
      "~perfect-scrollbar": resolve(
        __dirname,
        "node_modules/perfect-scrollbar",
      ),
      "~@fontsource": resolve(__dirname, "node_modules/@fontsource"),
    },
  },
});
