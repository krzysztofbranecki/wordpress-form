import { defineConfig } from 'vite';
import { commonConfig } from '../../common/commonConfig';

export default defineConfig(({ mode }) => {
    return commonConfig(mode);
});
