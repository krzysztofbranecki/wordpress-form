import { defineConfig } from 'vite';
import { commonConfig } from '../../common/commonConfig';

export default defineConfig(({ mode }) => {
    const config = commonConfig(mode);
    return config;
}); 