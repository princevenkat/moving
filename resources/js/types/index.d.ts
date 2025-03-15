import { Config } from 'ziggy-js';

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    stripe_account_active: boolean;
    vendor: {
      status: string;
      status_label: string;
      store_name: string;
      store_address: string;
      cover_image: string;
    };
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    csrf_token: string;
    error: String;
    success: String;
    auth: {
        user: User;
    };
    ziggy: Config & { location: string };
};
