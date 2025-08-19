import 'vuetify/styles';
import { createVuetify } from 'vuetify';

const vuetify = createVuetify({});
export default createVuetify({
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: '#1976d2',
                },
            },
            dark: {
                dark: true,
                colors: {
                    primary: '#0D47A1',
                    background: '#121212',
                },
            },
        },
    },
})
