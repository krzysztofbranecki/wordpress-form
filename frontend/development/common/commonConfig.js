import { createViteBlock } from 'vite-plugin-gutenberg-blocks';

export const commonConfig = (mode) => {
    return {
        plugins: [createViteBlock()],
        build: {
            emptyOutDir: true,
            sourcemap: mode === 'development',
            rollupOptions: {
                output: {
                    entryFileNames: 'index.js',
                    assetFileNames: '[name][extname]',
                },
            },
        },
        define:
            mode === 'development'
                ? {
                      'process.env.NODE_ENV': '"development"',
                  }
                : undefined,
    };
};
