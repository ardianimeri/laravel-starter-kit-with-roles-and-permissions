export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    roles: Role[];
    user_role: Role;
    permissions: string[];
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
export interface Role {
    id: number;
    name: string;
    guard_name: string;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    locale: string;
    translations: Record<string, string>;
    [key: string]: unknown;
}
