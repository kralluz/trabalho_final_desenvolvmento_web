import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import ReactApp from '@/components/react-app';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="React Router App">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            
            <div className="flex min-h-screen flex-col bg-[#FDFDFC] dark:bg-[#0a0a0a]">
                {/* Header com navegação Inertia */}
                <header className="w-full border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                    <div className="container mx-auto flex items-center justify-between">
                        <div className="text-xl font-bold">
                            Laravel + React Router
                        </div>
                        <nav className="flex items-center gap-4">
                        {auth.user ? (
                            <Link
                                    href="/dashboard"
                                className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                            >
                                Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                        href="/login"
                                    className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                                >
                                    Log in
                                </Link>
                                <Link
                                        href="/register"
                                    className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                            
                            <Link
                                href="/api-exemplo"
                                className="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                            >
                                Exemplo API (Inertia)
                            </Link>
                        </nav>
                        </div>
                </header>
                
                {/* Aplicação React com React Router */}
                <main className="flex-grow">
                    <ReactApp />
                    </main>
            </div>
        </>
    );
}
