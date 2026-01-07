import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, useForm, usePage } from '@inertiajs/react';

export default function Index({ projects }) {
  const { flash } = usePage().props;

  const { data, setData, post, processing, errors, reset, clearErrors } = useForm({
    name: '',
    description: '',
  });

  const submit = (e) => {
    e.preventDefault();
    post(route('projects.store'), {
      preserveScroll: true,
      onSuccess: () => {
        reset();
        clearErrors();
      },
    });
  };

  return (
    <AuthenticatedLayout>
      <Head title="Projects" />

      {flash?.success && (
        <div className="max-w-5xl mx-auto px-6 pt-6">
          <div className="rounded border border-green-200 bg-green-50 text-green-800 px-4 py-2">
            {flash.success}
          </div>
        </div>
      )}

      <div className="max-w-5xl mx-auto p-6 space-y-6">
        <div className="bg-white rounded-xl shadow p-6">
          <h1 className="text-xl font-semibold mb-4">Projects</h1>

          <form onSubmit={submit} className="space-y-3">
            <div>
              <label className="block text-sm font-medium">Name</label>
              <input
                className="mt-1 w-full rounded border-gray-300"
                value={data.name}
                onChange={(e) => setData('name', e.target.value)}
              />
              {errors.name && <div className="text-sm text-red-600 mt-1">{errors.name}</div>}
            </div>

            <div>
              <label className="block text-sm font-medium">Description (optional)</label>
              <textarea
                className="mt-1 w-full rounded border-gray-300"
                value={data.description}
                onChange={(e) => setData('description', e.target.value)}
              />
              {errors.description && (
                <div className="text-sm text-red-600 mt-1">{errors.description}</div>
              )}
            </div>

            <button
              type="submit"
              className="px-4 py-2 rounded bg-black text-white disabled:opacity-50"
              disabled={processing}
            >
              Create project
            </button>
          </form>
        </div>

        <div className="bg-white rounded-xl shadow p-6">
          <h2 className="text-lg font-semibold mb-4">Your projects</h2>
          <div className="space-y-2">
            {projects.map((p) => (
              <div key={p.id} className="flex items-center justify-between border rounded p-3">
                <div>
                  <div className="font-medium">{p.name}</div>
                  <div className="text-sm text-gray-600">
                    keywords: {p.keywords_count} · briefs: {p.briefs_count}
                  </div>
                </div>
                <Link className="underline" href={route('projects.show', p.id)}>
                  Open
                </Link>
              </div>
            ))}
            {projects.length === 0 && <div className="text-gray-600">No projects yet.</div>}
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}